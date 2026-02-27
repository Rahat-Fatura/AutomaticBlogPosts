const puppeteer = require('puppeteer');

// Analiz (gib.gov.tr/duyuru-arsivi):
// - Next.js app, client-side rendering
// - Duyurular dinamik yükleniyor
// - Detay sayfalarına gitmek gerekiyor

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
    
    // Try common content selectors
    const selectors = [
      'article',
      '[role="main"]',
      'main',
      '.content',
      '.article-content',
      '.post-content',
      '#content'
    ];
    
    for (const sel of selectors) {
      const el = document.querySelector(sel);
      if (el) {
        const text = clean(el.innerText);
        if (text && text.length > 100) return text;
      }
    }
    
    // Fallback: get largest text block
    const blocks = Array.from(document.querySelectorAll('div, section, article'))
      .map((el) => ({ el, len: clean(el.innerText).length }))
      .filter((x) => x.len > 100)
      .sort((a, b) => b.len - a.len);
    
    if (!blocks.length) return '';
    return clean(blocks[0].el.innerText);
  });
  
  return cleanText(txt);
}

module.exports = async function scrapeGibDuyuru() {
  const sourceUrl = 'https://gib.gov.tr/duyuru-arsivi';
  const scrapedAt = new Date().toISOString();
  
  let browser;
  try {
    browser = await puppeteer.launch({
      headless: true,
      args: ['--ignore-certificate-errors', '--no-sandbox'],
    });
    
    const page = await browser.newPage();
    page.setDefaultNavigationTimeout(30000);
    
    await page.goto(sourceUrl, { waitUntil: 'networkidle0' });
    
    // Wait for content to load (Next.js app)
    await new Promise(resolve => setTimeout(resolve, 3000));
    
    const duyurular = await page.evaluate(() => {
      const baseUrl = location.origin;
      const clean = (s) => (s || '').replace(/\s+/g, ' ').trim();
      
      const items = [];
      
      // Try to find announcement links
      const links = Array.from(document.querySelectorAll('a[href*="/duyuru"]'));
      
      for (const link of links) {
        const href = link.getAttribute('href') || '';
        if (!href || href === '/duyuru-arsivi') continue;
        
        const fullLink = (() => {
          try {
            return new URL(href, baseUrl).toString();
          } catch {
            return href;
          }
        })();
        
        // Extract title from link text or nearby elements
        const title = clean(link.innerText || link.textContent || '');
        if (!title || title.length < 5) continue;
        
        // Try to find date nearby
        let tarih = '';
        const parent = link.closest('div, li, article');
        if (parent) {
          const text = clean(parent.innerText);
          const dateMatch = text.match(/\d{2}[./-]\d{2}[./-]\d{4}/);
          if (dateMatch) tarih = dateMatch[0];
        }
        
        items.push({
          baslik: title,
          tarih: tarih,
          link: fullLink,
          ozet: ''
        });
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
    
    console.log(`Found ${duyurular.length} announcements from gib.gov.tr`);
    
    // Fetch detail pages
    for (const item of duyurular) {
      try {
        await page.goto(item.link, { waitUntil: 'networkidle0', timeout: 20000 });
        await new Promise(resolve => setTimeout(resolve, 2000));
        item.ozet = await extractDetailText(page);
      } catch (e) {
        console.error(`Error fetching detail for ${item.link}:`, e.message);
        // keep existing ozet (empty)
      }
    }
    
    return {
      source_name: 'gib.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: duyurular,
    };
  } catch (e) {
    return {
      source_name: 'gib.gov.tr',
      source_url: sourceUrl,
      scraped_at: scrapedAt,
      duyurular: [],
      error: e && e.message ? e.message : String(e),
    };
  } finally {
    if (browser) await browser.close().catch(() => {});
  }
};
