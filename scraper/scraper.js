const fs = require('fs');
const path = require('path');

const { spawn } = require('child_process');

const scrapeEbelgeGib = require('./sites/ebelge-gib');
const scrapeDijitalGib = require('./sites/dijital-gib');
const scrapeTicaret = require('./sites/ticaret');
const scrapeGgmTicaret = require('./sites/ggm-ticaret');
const scrapeGibDuyuru = require('./sites/gib-duyuru');

function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

function withTimeout(promise, ms, label) {
  let t;
  const timeout = new Promise((_, reject) => {
    t = setTimeout(() => reject(new Error(`${label} timed out after ${ms}ms`)), ms);
  });
  return Promise.race([promise, timeout]).finally(() => clearTimeout(t));
}

function runSiteInChild(siteKey, ms, label) {
  return new Promise((resolve, reject) => {
    const runnerPath = path.join(__dirname, 'run-site.js');
    const child = spawn(process.execPath, [runnerPath, siteKey], {
      cwd: process.cwd(),
      stdio: ['ignore', 'pipe', 'pipe'],
      env: process.env,
    });

    let stdout = '';
    let stderr = '';

    const t = setTimeout(() => {
      const err = new Error(`${label} timed out after ${ms}ms`);
      try {
        child.kill('SIGKILL');
      } catch (_) {
        // ignore
      }
      reject(err);
    }, ms);

    child.stdout?.on('data', (buf) => {
      stdout += buf.toString('utf-8');
    });
    child.stderr?.on('data', (buf) => {
      stderr += buf.toString('utf-8');
    });

    child.on('error', (err) => {
      clearTimeout(t);
      reject(err);
    });

    child.on('close', (code) => {
      clearTimeout(t);
      if (code !== 0) {
        reject(new Error(stderr || `Site scraper exited with code ${code ?? 'unknown'}`));
        return;
      }

      try {
        const json = JSON.parse(stdout);
        resolve(json);
      } catch (e) {
        reject(new Error(`Invalid JSON from site scraper: ${stderr || (e && e.message ? e.message : String(e))}`));
      }
    });
  });
}

async function main() {
  const scrapedAt = new Date().toISOString();

  const sources = [];

  const jobs = [
    {
      name: 'ebelge.gib.gov.tr',
      url: 'https://ebelge.gib.gov.tr/duyurular.html',
      fn: scrapeEbelgeGib,
      key: 'ebelge',
    },
    {
      name: 'dijital.gib.gov.tr',
      url: 'https://dijital.gib.gov.tr/duyurular#4',
      fn: scrapeDijitalGib,
      key: 'dijital',
    },
    {
      name: 'ticaret.gov.tr',
      url: 'https://ticaret.gov.tr/duyurular',
      fn: scrapeTicaret,
      key: 'ticaret',
    },
    {
      name: 'ggm.ticaret.gov.tr',
      url: 'https://ggm.ticaret.gov.tr/',
      fn: scrapeGgmTicaret,
      key: 'ggm',
    },
    {
      name: 'gib.gov.tr',
      url: 'https://gib.gov.tr/duyuru-arsivi',
      fn: scrapeGibDuyuru,
      key: 'gib',
    },
  ];

  for (const job of jobs) {
    let res;
    try {
      console.log(`⏳ ${job.name}: başlıyor...`);
      // Run in a separate process so we can hard-kill hung Puppeteer/HTTP work.
      // NOTE: Keep the old in-process fn as a fallback for environments where spawn is blocked.
      // ebelge-gib needs more time for PDF downloads and detail page fetching
      const timeout = job.key === 'ebelge' ? 5 * 60 * 1000 : 2 * 60 * 1000;
      res = await runSiteInChild(job.key, timeout, job.name).catch(() => withTimeout(job.fn(), timeout, job.name));
    } catch (e) {
      res = {
        source_name: job.name,
        source_url: job.url,
        scraped_at: new Date().toISOString(),
        count: 0,
        duyurular: [],
        error: e && e.message ? e.message : String(e),
      };
    }

    const count = Array.isArray(res?.duyurular) ? res.duyurular.length : 0;
    if (res?.error) {
      console.log(`❌ ${job.name}: hata - ${res.error}`);
    } else {
      console.log(`✅ ${job.name}: ${count} duyuru çekildi`);
    }

    sources.push({
      source_name: job.name,
      source_url: job.url,
      scraped_at: res?.scraped_at || new Date().toISOString(),
      count,
      duyurular: res?.duyurular || [],
      ...(res?.error ? { error: res.error } : {}),
    });

    await sleep(2000);
  }

  const totalCount = sources.reduce((acc, s) => acc + (s.count || 0), 0);
  const output = {
    meta: {
      scraped_at: scrapedAt,
      total_count: totalCount,
    },
    sources,
  };

  const outDir = path.join(__dirname, 'output');
  const outPath = path.join(outDir, 'duyurular.json');

  await fs.promises.mkdir(outDir, { recursive: true });
  await fs.promises.writeFile(outPath, JSON.stringify(output, null, 2), 'utf-8');
}

main().catch((e) => {
  console.error(e);
  process.exitCode = 1;
});
