const puppeteer = require('puppeteer');

// Analiz (ggm.ticaret.gov.tr):
// - Yapı: Duyurular listesi `https://ggm.ticaret.gov.tr/duyurular` altında `li > a > div.text > h5` şeklinde.
// - Link: `a[href*="/duyurular/"]`.
// - Tarih: Liste sayfasında görünmüyor; tarih gerekiyorsa detay sayfadan çekilebilir.
// - Pagination/lazy-load: Belirgin değil.
// - waitForSelector: `a[href*="/duyurular/"]`

function toAbsoluteUrl(baseUrl, href) {
  try {
    return new URL(href, baseUrl).toString();
  } catch {
    return href;
  }
}

function cleanText(s) {
  return (s || '').replace(/\s+/g, ' ').trim();
}

async function extractDetailText(page) {
  const txt = await page.evaluate(() => {
    const clean = (s) => (s || '').replace(/\s+/g, ' ').trim();

    const preferredSelectors = [
      'article',
      'main',
      '.__content',
      '.content',
    ];

    for (const sel of preferredSelectors) {
      const el = document.querySelector(sel);
      if (!el) continue;
      const t = clean(el.innerText);
      if (t && t.length > 150) return t;
    }

    const blocks = Array.from(document.querySelectorAll('div, section, article'))
      .map((el) => ({ el, len: clean(el.innerText).length }))
      .filter((x) => x.len > 150)
      .sort((a, b) => b.len - a.len);

    if (!blocks.length) return '';
    return clean(blocks[0].el.innerText);
  });

  return cleanText(txt);
}

module.exports = async function scrapeGgmTicaret() {
  const sourceUrl = 'https://ggm.ticaret.gov.tr/duyurular';
  const scrapedAt = new Date().toISOString();

  let browser;
  try {
    browser = await puppeteer.launch({
      headless: true,
      args: ['--ignore-certificate-errors', '--no-sandbox'],
    });

    const page = await browser.newPage();
    page.setDefaultNavigationTimeout(30000);

    await page.goto(sourceUrl, { waitUntil: 'domcontentloaded' });
    await page.waitForSelector('a[href*="/duyurular/"]', { timeout: 30000 });

    const duyurular = await page.evaluate(() => {
      const clean = (s) => (s || '').replace(/\s+/g, ' ').trim();

      const links = Array.from(document.querySelectorAll('a[href*="/duyurular/"]'));
      const out = [];

      for (const a of links) {
        const baslik = clean(a.querySelector('h5')?.innerText) || clean(a.innerText);
        const link = a.href;
        if (!baslik || baslik.length < 6) continue;
        if (!link) continue;
        out.push({ baslik, tarih: '', link, ozet: '' });
      }

      // Deduplicate by link
      const seen = new Set();
      const uniq = [];
      for (const it of out) {
        if (seen.has(it.link)) continue;
        seen.add(it.link);
        uniq.push(it);
      }
      return uniq;
    });

    const normalized = (duyurular || [])
      .map((d) => ({
        baslik: cleanText(d.baslik),
        tarih: cleanText(d.tarih),
        link: toAbsoluteUrl(sourceUrl, d.link),
        ozet: cleanText(d.ozet),
      }))
      .filter((d) => d.baslik && d.link);

    for (const item of normalized) {
      try {
        await page.goto(item.link, { waitUntil: 'domcontentloaded' });
        item.ozet = await extractDetailText(page);
      } catch {
        // keep existing `ozet`
      }
    }

    return {
      source_name: 'ggm.ticaret.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: normalized,
    };
  } catch (e) {
    return {
      source_name: 'ggm.ticaret.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: [],
      error: e && e.message ? e.message : String(e),
    };
  } finally {
    if (browser) await browser.close().catch(() => {});
  }
};
