const puppeteer = require('puppeteer');
const https = require('https');
const pdfParse = require('pdf-parse');

// Analiz (ebelge.gib.gov.tr/duyurular.html):
// - Yapı: Duyurular statik HTML içinde, ana içerik `.component-content` altında.
// - Tarih: Genelde `p.nspText` içinde `dd.MM.yyyy` formatında.
// - Link: Aynı blokta `a[href]` ile; çoğu zaman anchor text'i "tıklayınız.".
// - Başlık/özet: Aynı `p.nspText` metninden türetmek gerekiyor.
// - Pagination/lazy-load: Gözlemlenmedi (tek sayfa uzun liste).
// - waitForSelector: `.component-content`

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

function deriveTitleAndSummary(text) {
  const t = cleanText(text);
  if (!t) return { baslik: '', ozet: '' };

  // Remove trailing boilerplate
  let x = t.replace(/\bDuyurulur\.?$/i, '').trim();

  // Title heuristic: take first sentence up to first period, else first 120 chars.
  const dotIdx = x.indexOf('.');
  const baslik = cleanText(dotIdx > 20 ? x.slice(0, dotIdx + 1) : x.slice(0, 120));
  const ozet = cleanText(x);
  return { baslik, ozet };
}

function isDownloadLink(url) {
  return /\.(pdf|zip|rar|7z|doc|docx|xls|xlsx)$/i.test(url || '');
}

function isPdfLink(url) {
  return /\.pdf$/i.test(url || '');
}

function fetchBuffer(url, opts = {}) {
  const timeoutMs = typeof opts.timeoutMs === 'number' ? opts.timeoutMs : 20000;
  const maxBytes = typeof opts.maxBytes === 'number' ? opts.maxBytes : 10 * 1024 * 1024;

  return new Promise((resolve, reject) => {
    const req = https.get(url, (res) => {
        if (res.statusCode && res.statusCode >= 300 && res.statusCode < 400 && res.headers.location) {
          const redirected = new URL(res.headers.location, url).toString();
          res.resume();
          fetchBuffer(redirected, opts).then(resolve, reject);
          return;
        }

        if (res.statusCode !== 200) {
          res.resume();
          reject(new Error(`HTTP ${res.statusCode}`));
          return;
        }

        const chunks = [];
        let total = 0;
        res.on('data', (c) => chunks.push(c));
        res.on('data', (c) => {
          total += c.length;
          if (total > maxBytes) {
            req.destroy(new Error(`Download too large (> ${maxBytes} bytes)`));
          }
        });
        res.on('end', () => resolve(Buffer.concat(chunks)));
      });

    req.setTimeout(timeoutMs, () => {
      req.destroy(new Error(`Request timeout after ${timeoutMs}ms`));
    });

    req.on('error', reject);
  });
}

async function extractPdfText(url) {
  const buf = await fetchBuffer(url, { timeoutMs: 20000, maxBytes: 10 * 1024 * 1024 });
  const data = await pdfParse(buf);
  return cleanText(data.text);
}

async function extractDetailText(page) {
  const txt = await page.evaluate(() => {
    const clean = (s) => (s || '').replace(/\s+/g, ' ').trim();
    const container = document.querySelector('.component-content') || document.querySelector('#rt-mainbody') || document.body;
    const t = clean(container.innerText);
    if (t && t.length > 150) return t;

    const blocks = Array.from(document.querySelectorAll('div, section, article'))
      .map((el) => ({ el, len: clean(el.innerText).length }))
      .filter((x) => x.len > 150)
      .sort((a, b) => b.len - a.len);

    if (!blocks.length) return '';
    return clean(blocks[0].el.innerText);
  });

  return cleanText(txt);
}

module.exports = async function scrapeEbelgeGib() {
  const sourceUrl = 'https://ebelge.gib.gov.tr/duyurular.html';
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
    await page.waitForSelector('.component-content', { timeout: 30000 });

    const duyurular = await page.evaluate(() => {
      const baseUrl = location.origin;
      const clean = (s) => (s || '').replace(/\s+/g, ' ').trim();
      const dateRe = /\b\d{2}\.\d{2}\.\d{4}\b/;

      const container = document.querySelector('.component-content') || document.body;
      const ps = Array.from(container.querySelectorAll('p.nspText, p'));

      const items = [];
      for (const p of ps) {
        const text = clean(p.innerText);
        if (!text || !dateRe.test(text)) continue;

        const tarih = (text.match(dateRe) || [null])[0];
        const a = p.querySelector('a[href]') || p.nextElementSibling?.querySelector?.('a[href]') || null;
        if (!a) continue;

        const href = a.getAttribute('href') || '';
        const link = (() => {
          try {
            return new URL(href, baseUrl).toString();
          } catch {
            return href;
          }
        })();

        // If anchor text is generic, derive baslik/ozet from paragraph text.
        const aText = clean(a.innerText);
        const raw = text.replace(dateRe, '').trim();

        let baslik = aText;
        let ozet = raw;

        if (!baslik || /tıklayınız\.?/i.test(baslik) || baslik.length < 8) {
          // Title heuristic: first sentence.
          const dotIdx = raw.indexOf('.');
          baslik = clean(dotIdx > 20 ? raw.slice(0, dotIdx + 1) : raw.slice(0, 120));
        }

        if (!baslik) continue;

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

    // Normalize absolute URLs (defensive)
    const normalized = (duyurular || []).map((d) => ({
      baslik: cleanText(d.baslik),
      tarih: cleanText(d.tarih),
      link: toAbsoluteUrl(sourceUrl, d.link),
      ozet: cleanText(d.ozet),
    })).filter((d) => d.baslik && d.link);

    // OPTIMIZATION: Skip PDF/detail fetching to avoid timeout
    // The list already contains sufficient summary information
    // Fetching 29 PDFs and detail pages takes >5 minutes
    
    // for (const item of normalized) {
    //   try {
    //     if (isPdfLink(item.link)) {
    //       const pdfText = await extractPdfText(item.link);
    //       if (pdfText) item.ozet = pdfText;
    //       continue;
    //     }
    //
    //     if (isDownloadLink(item.link)) continue;
    //
    //     await page.goto(item.link, { waitUntil: 'domcontentloaded' });
    //     item.ozet = await extractDetailText(page);
    //   } catch {
    //     // keep existing `ozet`
    //   }
    // }

    return {
      source_name: 'ebelge.gib.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: normalized,
    };
  } catch (e) {
    return {
      source_name: 'ebelge.gib.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: [],
      error: e && e.message ? e.message : String(e),
    };
  } finally {
    if (browser) await browser.close().catch(() => {});
  }
};
