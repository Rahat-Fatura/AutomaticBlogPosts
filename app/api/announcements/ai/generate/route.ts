import { NextResponse } from 'next/server'
import { prisma } from '@/lib/prisma'
import path from 'path'
import fs from 'fs/promises'
import crypto from 'crypto'

export const runtime = 'nodejs'

function sha256(input: string) {
  return crypto.createHash('sha256').update(input).digest('hex')
}

async function ensureAnnouncementExistsByOriginalUrl(originalUrl: string) {
  const found = await prisma.announcement.findUnique({ where: { originalUrl } })
  if (found) return found

  const outPath = path.join(process.cwd(), 'scraper', 'output', 'duyurular.json')
  const raw = await fs.readFile(outPath, 'utf-8')
  const json = JSON.parse(raw) as {
    meta?: { scraped_at?: string }
    sources?: Array<{
      source_name?: string
      source_url?: string
      scraped_at?: string
      duyurular?: Array<{ baslik?: string; ozet?: string; link?: string; tarih?: string }>
    }>
  }

  const sources = Array.isArray(json.sources) ? json.sources : []
  for (const src of sources) {
    const duyurular = Array.isArray(src?.duyurular) ? src.duyurular : []
    const item = duyurular.find((d) => String(d?.link || '').trim() === originalUrl)
    if (!item) continue

    const sourceName = String(src?.source_name || '').trim()
    const sourceUrl = String(src?.source_url || '').trim()
    if (!sourceName || !sourceUrl) break

    const title = String(item?.baslik || '').trim()
    const content = String(item?.ozet || '').trim()
    if (!title) break

    const fetchedAtIso = String(src?.scraped_at || json.meta?.scraped_at || new Date().toISOString())
    const fetchedAt = new Date(fetchedAtIso)

    const dbSource = await prisma.source.upsert({
      where: { url: sourceUrl },
      create: { name: sourceName, url: sourceUrl },
      update: { name: sourceName },
    })

    const contentHash = sha256(`${title}|${originalUrl}`)
    const exists = await prisma.announcement.findFirst({
      where: { OR: [{ contentHash }, { originalUrl }] },
      select: { id: true },
    })
    if (exists) {
      return await prisma.announcement.findUnique({ where: { originalUrl } })
    }

    return await prisma.announcement.create({
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
  }

  return null
}

function extractRetryAfterSeconds(raw: unknown): number | null {
  if (!raw || typeof raw !== 'object') return null
  if (!('error' in raw)) return null
  const err = (raw as { error?: unknown }).error
  if (!err || typeof err !== 'object') return null
  const details = (err as { details?: unknown }).details
  if (!Array.isArray(details)) return null

  const retryInfo = details.find(
    (d) =>
      d &&
      typeof d === 'object' &&
      '@type' in d &&
      (d as { '@type'?: unknown })['@type'] === 'type.googleapis.com/google.rpc.RetryInfo'
  ) as null | { retryDelay?: unknown }

  const retryDelay = retryInfo?.retryDelay
  if (typeof retryDelay !== 'string') return null
  const m = retryDelay.match(/^(\d+)s$/)
  if (!m) return null
  const s = Number(m[1])
  return Number.isFinite(s) ? s : null
}


function toStringOrNull(v: unknown): string | null {
  if (typeof v !== 'string') return null
  const t = v.trim()
  return t ? t : null
}

async function callGeminiJson(prompt: string) {
  const apiKey = process.env.GEMINI_API_KEY
  if (!apiKey) throw new Error('GEMINI_API_KEY eksik. .env.local içine ekleyin.')

  const preferredModel = (process.env.GEMINI_MODEL || '').trim()
  const candidates = [
    preferredModel,
    'gemini-2.5-flash',
    'gemini-2.5-pro',
    'gemini-1.5-flash',
    'gemini-1.5-pro',
    'gemini-2.0-flash',
  ].filter(Boolean)

  let last: { status: number; raw: unknown } | null = null

  for (const model of candidates) {
    const url = `https://generativelanguage.googleapis.com/v1beta/models/${encodeURIComponent(model)}:generateContent?key=${encodeURIComponent(apiKey)}`

    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        contents: [{ role: 'user', parts: [{ text: prompt }] }],
        generationConfig: {
          temperature: 0.7,
          maxOutputTokens: 8192,
          responseMimeType: 'application/json',
          responseSchema: {
            type: 'object',
            properties: {
              title: { type: 'string' },
              excerpt: { type: 'string' },
              metaDescription: { type: 'string' },
              contentHtml: { type: 'string' },
            },
            required: ['title', 'excerpt', 'metaDescription', 'contentHtml'],
          },
        },
      }),
    })

    const raw = (await res.json().catch(() => null)) as unknown

    if (res.ok) return { raw, model }

    last = { status: res.status || 500, raw }

    const msg = JSON.stringify(raw)
    const retryableNotFound = res.status === 404 || msg.includes('NOT_FOUND') || msg.includes('not found')
    const retryableUnsupported = msg.includes('not supported') || msg.includes('generateContent')
    if (!(retryableNotFound || retryableUnsupported)) break
  }

  const retryAfterSeconds = extractRetryAfterSeconds(last?.raw)
  const detail = last?.raw ? JSON.stringify(last.raw) : 'Gemini isteği başarısız.'
  const err = new Error(retryAfterSeconds != null ? `${detail} (retryAfterSeconds=${retryAfterSeconds})` : detail)
  ;(err as { status?: number; retryAfterSeconds?: number }).status = last?.status || 500
  if (retryAfterSeconds != null) (err as { retryAfterSeconds?: number }).retryAfterSeconds = retryAfterSeconds
  throw err
}


export async function POST(req: Request) {
  try {
    const body = (await req.json().catch(() => null)) as null | { originalUrl?: unknown }
    const originalUrl = typeof body?.originalUrl === 'string' ? body.originalUrl.trim() : ''
    if (!originalUrl) return NextResponse.json({ error: 'originalUrl gerekli.' }, { status: 400 })

    const ensured = await ensureAnnouncementExistsByOriginalUrl(originalUrl)
    if (!ensured) return NextResponse.json({ error: 'Kayıt bulunamadı. Önce DB sync yap.' }, { status: 404 })

    const ann = (await prisma.announcement.findUnique({
      where: { originalUrl },
      select: { id: true, title: true, content: true, aiStatus: true } as any,
    })) as any

    if (!ann) return NextResponse.json({ error: 'Kayıt bulunamadı. Önce DB sync yap.' }, { status: 404 })

    const rawText = String(ann?.content ?? '').trim()
    if (!rawText || rawText.length < 50) {
      return NextResponse.json({ error: 'Raw content çok kısa. Önce scraper full metni çekmeli.' }, { status: 400 })
    }

    await prisma.announcement.update({
      where: { id: ann.id },
      data: { aiStatus: 'running', aiError: null, aiUpdatedAt: new Date() } as any,
    })

    const prompt = `Aşağıdaki HAM METİN, bir kurum duyurusu/makalesidir. Bunu Türkçe, modern bir blog yazısına dönüştür.

Kurallar:
- Çıktıyı SADECE geçerli JSON olarak ver. Başka hiçbir şey yazma.
- JSON alanları:
  - title: string
  - excerpt: string (max 260 karakter)
  - metaDescription: string (SEO açıklaması, max 155 karakter)
  - contentHtml: string (HTML; <h2>, <p>, <ul><li> kullan; kaynak linki en sonda ver)
- İçerik özgün olsun, ama HAM METİN'deki gerçekleri bozma.
- Eğer tarih/kurum/başvuru şartları gibi bilgiler varsa koru.

KAYNAK URL: ${originalUrl}

HAM METİN:
${rawText}`

    const { raw } = await callGeminiJson(prompt)
    
    // Extract JSON from candidates[0].content.parts[0].text
    let jsonData: unknown = null
    let finishReason: string | null = null
    
    if (raw && typeof raw === 'object') {
      const candidates = (raw as { candidates?: unknown }).candidates
      if (Array.isArray(candidates) && candidates.length > 0) {
        const first = candidates[0] as { content?: { parts?: Array<{ text?: unknown }> }; finishReason?: unknown }
        finishReason = typeof first?.finishReason === 'string' ? first.finishReason : null
        
        const parts = first?.content?.parts
        if (Array.isArray(parts) && parts.length > 0) {
          const textContent = parts[0]?.text
          if (typeof textContent === 'string') {
            try {
              jsonData = JSON.parse(textContent)
            } catch (err) {
              console.error('JSON parse error:', err)
              console.error('Text content:', textContent)
              throw new Error(`Gemini JSON parse hatası: ${err instanceof Error ? err.message : String(err)}`)
            }
          }
        }
      }
    }
    
    if (finishReason === 'MAX_TOKENS') {
      throw new Error('Gemini token limitine ulaştı. İçerik çok uzun, prompt kısaltılmalı veya maxOutputTokens artırılmalı.')
    }
    
    const parsed = (jsonData || {}) as {
      title?: unknown
      excerpt?: unknown
      metaDescription?: unknown
      contentHtml?: unknown
    }

    const aiTitle = toStringOrNull(parsed.title)
    const aiExcerpt = toStringOrNull(parsed.excerpt)
    const aiMetaDescription = toStringOrNull(parsed.metaDescription)
    const aiContent = toStringOrNull(parsed.contentHtml)

    if (!aiTitle || !aiContent) {
      throw new Error('AI çıktısı beklenen JSON formatında değil (title/contentHtml).')
    }

    const updated = await prisma.announcement.update({
      where: { id: ann.id },
      data: {
        aiStatus: 'done',
        aiTitle,
        aiExcerpt,
        aiMetaDescription,
        aiContent,
        aiError: null,
        aiUpdatedAt: new Date(),
      } as any,
      select: {
        originalUrl: true,
        aiStatus: true,
        aiTitle: true,
        aiExcerpt: true,
        aiMetaDescription: true,
        aiContent: true,
        aiUpdatedAt: true,
      } as any,
    })

    return NextResponse.json(updated)
  } catch (e) {
    const status = e && typeof e === 'object' && 'status' in e ? Number((e as { status: unknown }).status) : 500
    const retryAfterSeconds =
      e && typeof e === 'object' && 'retryAfterSeconds' in e ? Number((e as { retryAfterSeconds: unknown }).retryAfterSeconds) : null

    const message = e instanceof Error ? e.message : String(e)

    return NextResponse.json(
      {
        error: message,
        ...(Number.isFinite(retryAfterSeconds as number) ? { retryAfterSeconds } : {}),
      },
      { status: Number.isFinite(status) ? status : 500 }
    )
  }
}
