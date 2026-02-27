const scrapeEbelgeGib = require('./sites/ebelge-gib');
const scrapeDijitalGib = require('./sites/dijital-gib');
const scrapeTicaret = require('./sites/ticaret');
const scrapeGgmTicaret = require('./sites/ggm-ticaret');
const scrapeGibDuyuru = require('./sites/gib-duyuru');

const site = process.argv[2];

const map = {
  ebelge: scrapeEbelgeGib,
  dijital: scrapeDijitalGib,
  ticaret: scrapeTicaret,
  ggm: scrapeGgmTicaret,
  gib: scrapeGibDuyuru,
};

async function main() {
  const fn = map[site];
  if (!fn) {
    throw new Error(`Unknown site: ${site}`);
  }

  const res = await fn();
  process.stdout.write(JSON.stringify(res));
}

main().catch((e) => {
  const err = e instanceof Error ? e.message : String(e);
  process.stderr.write(err);
  process.exitCode = 1;
});
