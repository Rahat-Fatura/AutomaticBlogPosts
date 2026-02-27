const puppeteer = require('puppeteer');

// Analiz (dijital.gib.gov.tr/duyurular#4):
// - Yapı: React/MUI tablı sayfa; hash ile kategori sekmeleri (#0, #1, #2, #3, #4 ...).
// - Liste: `#topBox` altında tekrar eden kartlar; her kartta tarih `dd/MM/yyyy` ve başlık bir link olarak geliyor.
// - Başlık: Kart içinde `a[href^="/duyurular#"]` linki ("Devamını Oku" olmayan link).
// - Tarih: Kart içinde kısa bir `p` metni olarak `dd/MM/yyyy`.
// - Pagination: MUI pagination (çok sayfa). Bu scraper ilk sayfadaki duyuruları çeker.
// - waitForSelector: `#topBox`

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

function sanitizeDetailText(raw, expectedTitle) {
  let t = cleanText(raw);
  if (!t) return '';

  t = t.replace(/\bDevamını Oku\b/gi, '').replace(/\s+/g, ' ').trim();

  // Drop leading UI bits
  t = t.replace(/^Geri Dön\s*/i, '').trim();

  // If title is present, cut everything before it.
  if (expectedTitle) {
    const lower = t.toLowerCase();
    const titleLower = cleanText(expectedTitle).toLowerCase();
    const idx = lower.indexOf(titleLower);
    if (idx >= 0) {
      t = t.slice(idx + expectedTitle.length).trim();
    }
  }

  // Remove dates that appear as standalone prefixes (e.g. 04.02.2026 or 04/02/2026)
  t = t.replace(/^\d{2}[./]\d{2}[./]\d{4}\s+/g, '').trim();

  // Cut off attachment/download sections.
  t = t.split(/\bDuyuru Ekleri\b/i)[0].trim();

  // Remove leftover download verbs if any.
  t = t.replace(/\b(Tüm Ekleri\s*İndir|İndir)\b/gi, '').replace(/\s+/g, ' ').trim();

  return t;
}

async function extractDetailText(page, expectedTitle) {
  const txt = await page.evaluate((title) => {
    const clean = (s) => (s || '').replace(/\s+/g, ' ').trim();
    const main = document.querySelector('#topBox') || document.querySelector('main') || document.body;

    const normalize = (s) => clean(s).toLowerCase();
    const titleN = normalize(title);

    const headings = Array.from(main.querySelectorAll('h1, h2, h3, h4, h5'))
      .map((h) => ({ h, t: clean(h.innerText) }))
      .filter((x) => x.t && x.t.length > 6)
      .filter((x) => x.t.toLowerCase() !== 'kategoriler');

    const exact = headings.find((x) => normalize(x.t) === titleN)?.h || null;
    const contains = headings.find((x) => normalize(x.t).includes(titleN) || titleN.includes(normalize(x.t)))?.h || null;
    const heading = exact || contains || headings.sort((a, b) => b.t.length - a.t.length)[0]?.h || null;

    const blacklist = ['Kategoriler', 'TEMİZLE', 'LİSTELE', 'Toplam duyuru sayısı', 'Sayfa'];
    const isBad = (t) => blacklist.some((b) => t.includes(b));

    if (heading) {
      // Prefer MUI paper/card containers.
      const preferred = heading.closest('.MuiPaper-root') || heading.closest('[class*="MuiPaper"]') || heading.closest('div');
      if (preferred) {
        const t = clean(preferred.innerText);
        if (t && t.length > 120 && !isBad(t) && t.length < 100000) return t;
      }

      // Climb up until text is clean and not an oversized wrapper.
      let node = heading.parentElement;
      while (node && node !== document.body) {
        const t = clean(node.innerText);
        if (t && t.length > 120 && !isBad(t) && t.length < 100000) return t;
        node = node.parentElement;
      }
    }

    // Fallback: largest block excluding list/filter areas.
    const candidates = Array.from(main.querySelectorAll('div, section, article'))
      .map((el) => {
        const t = clean(el.innerText);
        return { t, len: t.length };
      })
      .filter((x) => x.len > 120)
      .filter((x) => !isBad(x.t))
      .sort((a, b) => b.len - a.len);

    return candidates[0]?.t || '';
  }, expectedTitle);

  return sanitizeDetailText(txt, expectedTitle);
}

module.exports = async function scrapeDijitalGib() {
  const sourceUrl = 'https://dijital.gib.gov.tr/duyurular#4';
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
    await page.waitForSelector('#topBox', { timeout: 30000 });

    const duyurular = await page.evaluate(() => {
      const clean = (s) => (s || '').replace(/\s+/g, ' ').trim();
      const dateRe = /\b\d{2}\/\d{2}\/\d{4}\b/;

      const root = document.querySelector('#topBox') || document.querySelector('main') || document.body;

      // cards: try to locate per-item container by date paragraph
      const datePs = Array.from(root.querySelectorAll('p')).filter((p) => dateRe.test(clean(p.innerText)));
      const items = [];

      for (const p of datePs) {
        const tarih = (clean(p.innerText).match(dateRe) || [null])[0];
        if (!tarih) continue;

        const card = p.closest('div');
        if (!card) continue;

        const links = Array.from(card.querySelectorAll('a[href]'))
          .map((a) => ({
            text: clean(a.innerText),
            href: a.getAttribute('href') || '',
          }))
          .filter((x) => x.href);

        // title link: first non "Devamını Oku" link that looks like detail
        const titleLink = links.find((l) => l.text && !/devamını oku/i.test(l.text) && l.href.startsWith('/duyurular'));
        const moreLink = links.find((l) => /devamını oku/i.test(l.text) && l.href.startsWith('/duyurular'));

        const link = (titleLink || moreLink || links[0] || {}).href || '';
        const baslik = (titleLink || {}).text || '';

        // summary: take first paragraph text in card excluding date line
        const paragraphs = Array.from(card.querySelectorAll('p'))
          .map((pp) => clean(pp.innerText))
          .filter(Boolean);
        const ozet = paragraphs.find((t) => !dateRe.test(t) && t.length > 20) || '';

        if (!baslik || !link) continue;
        items.push({ baslik, tarih, link, ozet });
      }

      // Deduplicate by link
      const seen = new Set();
      const out = [];
      for (const it of items) {
        if (!it.link) continue;
        if (seen.has(it.link)) continue;
        seen.add(it.link);
        out.push(it);
      }
      return out;
    });

    const normalized = (duyurular || [])
      .map((d) => ({
        baslik: cleanText(d.baslik),
        tarih: cleanText(d.tarih),
        link: toAbsoluteUrl(sourceUrl, d.link),
        ozet: cleanText(d.ozet),
      }))
      .filter((d) => d.baslik && d.link);

    // Fetch detail content for each item.
    // Use a new Page per item to avoid SPA hash routing state issues.
    for (const item of normalized) {
      let detailPage;
      try {
        detailPage = await browser.newPage();
        detailPage.setDefaultNavigationTimeout(30000);

        await detailPage.goto(item.link, { waitUntil: 'networkidle2' });

        // Wait until the page renders the expected heading for this specific item.
        await detailPage.waitForFunction(
          (expected) => {
            const clean = (s) => (s || '').replace(/\s+/g, ' ').trim().toLowerCase();
            const exp = clean(expected);
            const main = document.querySelector('#topBox') || document.querySelector('main') || document.body;
            const hs = Array.from(main.querySelectorAll('h1,h2,h3,h4,h5'))
              .map((h) => clean(h.innerText))
              .filter(Boolean)
              .filter((t) => t !== 'kategoriler');
            if (!hs.length) return false;
            return hs.some((t) => t === exp || t.includes(exp) || exp.includes(t));
          },
          { timeout: 15000 },
          item.baslik
        );

        item.ozet = await extractDetailText(detailPage, item.baslik);
      } catch {
        // keep existing `ozet`
      } finally {
        if (detailPage) await detailPage.close().catch(() => {});
      }
    }

    return {
      source_name: 'dijital.gib.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: normalized,
    };
  } catch (e) {
    return {
      source_name: 'dijital.gib.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: [],
      error: e && e.message ? e.message : String(e),
    };
  } finally {
    if (browser) await browser.close().catch(() => {});
  }
};
