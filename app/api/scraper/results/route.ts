import { NextResponse } from 'next/server'
import path from 'path'
import fs from 'fs/promises'

export const runtime = 'nodejs'

export async function GET() {
  try {
    const outPath = path.join(process.cwd(), 'scraper', 'output', 'duyurular.json')
    const raw = await fs.readFile(outPath, 'utf-8')
    const json = JSON.parse(raw)

    return NextResponse.json(json)
  } catch {
    return NextResponse.json({ meta: { scraped_at: null, total_count: 0 }, sources: [] })
  }
}
