const puppeteer = require('puppeteer');

// Analiz (ticaret.gov.tr/duyurular):
// - Yapı: Duyurular sayfası listede `li > a > div.text > h5` başlık şeklinde.
// - Link: `a[href*="/duyurular/"]` (bazıları personel.ticaret.gov.tr alt domainine gidiyor).
// - Tarih: Çoğu kartta `div.text > p` içinde (ör: "21 Aralık 2025"); her kartta zorunlu değil.
// - Pagination/lazy-load: İlk bakışta yok; liste tek sayfada render ediliyor.
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
      'div.__content',
      'div.__zone',
      'article',
      'main',
    ];

    for (const sel of preferredSelectors) {
      const el = document.querySelector(sel);
      if (!el) continue;
      const t = clean(el.innerText);
      if (t && t.length > 200) return t;
    }

    const blocks = Array.from(document.querySelectorAll('div, section, article'))
      .map((el) => ({ el, len: clean(el.innerText).length }))
      .filter((x) => x.len > 200)
      .sort((a, b) => b.len - a.len);

    if (!blocks.length) return '';
    return clean(blocks[0].el.innerText);
  });

  return cleanText(txt);
}

module.exports = async function scrapeTicaret() {
  const sourceUrl = 'https://ticaret.gov.tr/duyurular';
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

      const out = [];
      const links = Array.from(document.querySelectorAll('a[href*="/duyurular/"], a[href^="https://personel.ticaret.gov.tr/duyurular/"]'));

      for (const a of links) {
        const card = a.closest('li') || a.closest('article') || a.closest('div');
        const baslik = clean(a.querySelector('h5')?.innerText) || clean(a.innerText);
        const link = a.href;

        if (!baslik || baslik.length < 6) continue;
        if (!link) continue;

        const p = card?.querySelector('div.text p') || card?.querySelector('p');
        const tarih = clean(p?.innerText) || '';

        out.push({ baslik, tarih, link, ozet: '' });
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

    // Fetch detail pages and fill `ozet` with full content.
    for (const item of normalized) {
      try {
        await page.goto(item.link, { waitUntil: 'domcontentloaded' });
        item.ozet = await extractDetailText(page);
      } catch {
        // keep existing `ozet`
      }
    }

    return {
      source_name: 'ticaret.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: normalized,
    };
  } catch (e) {
    return {
      source_name: 'ticaret.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: [],
      error: e && e.message ? e.message : String(e),
    };
  } finally {
    if (browser) await browser.close().catch(() => {});
  }
};
