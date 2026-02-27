import { NextResponse } from 'next/server'
import { prisma } from '@/lib/prisma'

export const runtime = 'nodejs'

type DraftQueryResult = {
  id: string
  announcementId: string
  title: string
  content: string
  metaDescription: string | null
  status: 'pending' | 'approved' | 'published' | 'rejected'
  createdAt: Date
  publishedAt: Date | null
  announcement: {
    originalUrl: string
    fetchedAt: Date
    source: {
      id: string
      name: string
      url: string
    }
  }
}

export async function GET() {
  const drafts = (await prisma.draft.findMany({
    orderBy: { createdAt: 'desc' },
    include: {
      announcement: {
        include: {
          source: true,
        },
      },
    },
  })) as unknown as DraftQueryResult[]

  return NextResponse.json(
    drafts.map((d: DraftQueryResult) => ({
      id: d.id,
      title: d.title,
      status: d.status,
      createdAt: d.createdAt,
      publishedAt: d.publishedAt,
      metaDescription: d.metaDescription,
      announcement: {
        id: d.announcementId,
        originalUrl: d.announcement.originalUrl,
        fetchedAt: d.announcement.fetchedAt,
        source: {
          id: d.announcement.source.id,
          name: d.announcement.source.name,
          url: d.announcement.source.url,
        },
      },
    }))
  )
}

export async function POST(req: Request) {
  try {
    const body = (await req.json()) as {
      title?: string
      content?: string
      metaDescription?: string | null
      status?: 'pending' | 'approved' | 'published' | 'rejected'
      originalUrl?: string
    }

    const title = (body.title || '').trim()
    const content = (body.content || '').trim()
    const originalUrl = (body.originalUrl || '').trim()

    if (!title || !content || !originalUrl) {
      return NextResponse.json({ error: 'title, content, originalUrl gerekli' }, { status: 400 })
    }

    const announcement = await prisma.announcement.findUnique({
      where: { originalUrl },
      select: { id: true },
    })

    if (!announcement) {
      return NextResponse.json(
        { error: 'Announcement bulunamadı. Önce /api/scraper/sync çalıştır.' },
        { status: 400 }
      )
    }

    const isPublished = body.status === 'published'

    const draft = await prisma.draft.upsert({
      where: { announcementId: announcement.id },
      create: {
        announcementId: announcement.id,
        title,
        content,
        metaDescription: body.metaDescription ?? null,
        status: body.status ?? 'pending',
        publishedAt: isPublished ? new Date() : null,
      },
      update: {
        title,
        content,
        metaDescription: body.metaDescription ?? null,
        status: body.status ?? 'pending',
        publishedAt: isPublished ? new Date() : null,
      },
    })

    return NextResponse.json({ id: draft.id })
  } catch (e) {
    const message = e instanceof Error ? e.message : String(e)
    return NextResponse.json({ error: message }, { status: 500 })
  }
}
