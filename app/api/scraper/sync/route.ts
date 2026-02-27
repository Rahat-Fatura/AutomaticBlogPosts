import { NextResponse } from 'next/server'
import path from 'path'
import fs from 'fs/promises'
import crypto from 'crypto'
import { prisma } from '@/lib/prisma'

export const runtime = 'nodejs'

type ScraperJson = {
  meta?: {
    scraped_at?: string
    total_count?: number
  }
  sources?: Array<{
    source_name?: string
    source_url?: string
    scraped_at?: string
    duyurular?: Array<{
      baslik?: string
      ozet?: string
      link?: string
      tarih?: string
    }>
  }>
}

function sha256(input: string) {
  return crypto.createHash('sha256').update(input).digest('hex')
}

export async function POST() {
  try {
    const outPath = path.join(process.cwd(), 'scraper', 'output', 'duyurular.json')
    const raw = await fs.readFile(outPath, 'utf-8')
    const json = JSON.parse(raw) as ScraperJson

    const sources = Array.isArray(json.sources) ? json.sources : []

    let synced = 0
    let skipped = 0

    for (const src of sources) {
      const sourceName = (src.source_name || '').trim()
      const sourceUrl = (src.source_url || '').trim()
      if (!sourceName || !sourceUrl) continue

      const fetchedAtIso = src.scraped_at || json.meta?.scraped_at || new Date().toISOString()
      const fetchedAt = new Date(fetchedAtIso)

      const dbSource = await prisma.source.upsert({
        where: { url: sourceUrl },
        create: { name: sourceName, url: sourceUrl },
        update: { name: sourceName },
      })

      const duyurular = Array.isArray(src.duyurular) ? src.duyurular : []
      for (const d of duyurular) {
        const title = (d.baslik || '').trim()
        const content = (d.ozet || '').trim()
        const originalUrl = (d.link || '').trim()
        if (!title || !originalUrl) continue

        const contentHash = sha256(`${title}|${originalUrl}`)

        const exists = await prisma.announcement.findFirst({
          where: {
            OR: [{ contentHash }, { originalUrl }],
          },
          select: { id: true },
        })

        if (exists) {
          skipped += 1
          continue
        }

        await prisma.announcement.create({
          data: {
            sourceId: dbSource.id,
            title,
            content,
            originalUrl,
            contentHash,
            status: 'new',
            fetchedAt: Number.isNaN(fetchedAt.getTime()) ? new Date() : fetchedAt,
          },
        })

        synced += 1
      }
    }

    return NextResponse.json({ synced, skipped })
  } catch (e) {
    const message = e instanceof Error ? e.message : String(e)
    return NextResponse.json({ status: 'error', message }, { status: 500 })
  }
}
