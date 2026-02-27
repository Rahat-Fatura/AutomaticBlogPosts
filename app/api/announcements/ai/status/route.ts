import { NextResponse } from 'next/server'
import { prisma } from '@/lib/prisma'

export async function POST(req: Request) {
  try {
    const body = (await req.json().catch(() => null)) as null | { originalUrls?: unknown }
    const originalUrls = Array.isArray(body?.originalUrls) ? body.originalUrls.filter((u): u is string => typeof u === 'string') : []

    if (originalUrls.length === 0) {
      return NextResponse.json({})
    }

    const announcements = await prisma.announcement.findMany({
      where: {
        originalUrl: { in: originalUrls },
      },
      select: {
        originalUrl: true,
        aiStatus: true,
        aiTitle: true,
        aiExcerpt: true,
        aiMetaDescription: true,
        aiContent: true,
        aiUpdatedAt: true,
      },
    })

    const result: Record<string, {
      aiStatus?: string
      aiTitle?: string | null
      aiExcerpt?: string | null
      aiMetaDescription?: string | null
      aiContent?: string | null
      aiUpdatedAt?: Date | null
    }> = {}

    for (const ann of announcements) {
      result[ann.originalUrl] = {
        aiStatus: ann.aiStatus,
        aiTitle: ann.aiTitle,
        aiExcerpt: ann.aiExcerpt,
        aiMetaDescription: ann.aiMetaDescription,
        aiContent: ann.aiContent,
        aiUpdatedAt: ann.aiUpdatedAt,
      }
    }

    return NextResponse.json(result)
  } catch (e) {
    const message = e instanceof Error ? e.message : String(e)
    return NextResponse.json({ error: message }, { status: 500 })
  }
}
