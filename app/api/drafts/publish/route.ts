import { NextResponse } from 'next/server'
import { prisma } from '@/lib/prisma'
import { wordpressClient } from '@/lib/wordpress'
import type { WPPost } from '@/lib/types'

export const runtime = 'nodejs'

type Body = {
  draftId?: string
}

export async function POST(req: Request) {
  try {
    const body = (await req.json().catch(() => null)) as Body | null
    const draftId = (body?.draftId || '').trim()

    if (!draftId) {
      return NextResponse.json({ error: 'draftId gerekli' }, { status: 400 })
    }

    const draft = await prisma.draft.findUnique({
      where: { id: draftId },
      include: {
        announcement: {
          select: {
            originalUrl: true,
          },
        },
      },
    })

    if (!draft) {
      return NextResponse.json({ error: 'Draft not found' }, { status: 404 })
    }

    const payload: Partial<WPPost> = {
      title: { rendered: draft.title, raw: draft.title },
      content: { rendered: draft.content, raw: draft.content },
      excerpt: { rendered: draft.metaDescription ?? '', raw: draft.metaDescription ?? '' },
      status: 'publish',
    }

    const post = await wordpressClient.createPost(payload)

    await prisma.draft.update({
      where: { id: draft.id },
      data: {
        status: 'published',
        publishedAt: new Date(),
      },
    })

    return NextResponse.json({ ok: true, postId: post.id, post })
  } catch (e) {
    const message = e instanceof Error ? e.message : String(e)
    return NextResponse.json({ error: message }, { status: 500 })
  }
}
