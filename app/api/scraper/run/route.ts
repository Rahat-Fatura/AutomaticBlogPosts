import { NextResponse } from 'next/server'
import { spawn } from 'child_process'
import path from 'path'
import fs from 'fs/promises'

export const runtime = 'nodejs'

function runScraper() {
  return new Promise<void>((resolve, reject) => {
    const scraperPath = path.join(process.cwd(), 'scraper', 'scraper.js')

    const child = spawn(process.execPath, [scraperPath], {
      cwd: process.cwd(),
      stdio: ['ignore', 'pipe', 'pipe'],
      env: process.env,
    })

    child.stdout?.on('data', (buf) => {
      process.stdout.write(buf)
    })
    child.stderr?.on('data', (buf) => {
      process.stderr.write(buf)
    })

    child.on('error', (err) => reject(err))
    child.on('close', (code) => {
      if (code === 0) resolve()
      else reject(new Error(`Scraper exited with code ${code ?? 'unknown'}`))
    })
  })
}

async function readCount() {
  const outPath = path.join(process.cwd(), 'scraper', 'output', 'duyurular.json')
  const raw = await fs.readFile(outPath, 'utf-8')
  const json = JSON.parse(raw) as { meta?: { total_count?: number } }
  const count = json?.meta?.total_count
  return typeof count === 'number' ? count : 0
}

export async function POST() {
  try {
    await runScraper()
    const count = await readCount().catch(() => 0)
    return NextResponse.json({ status: 'done', count })
  } catch (e) {
    const message = e instanceof Error ? e.message : String(e)
    return NextResponse.json({ status: 'error', message }, { status: 500 })
  }
}
