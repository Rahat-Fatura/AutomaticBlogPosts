import { NextResponse } from 'next/server'
import { prisma } from '@/lib/prisma'

export const runtime = 'nodejs'

export async function GET(_: Request, ctx: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await ctx.params

    const draft = await prisma.draft.findUnique({
      where: { id },
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

    return NextResponse.json({
      id: draft.id,
      title: draft.title,
      content: draft.content,
      metaDescription: draft.metaDescription,
      status: draft.status,
      originalUrl: draft.announcement.originalUrl,
      createdAt: draft.createdAt,
      publishedAt: draft.publishedAt,
    })
  } catch (e) {
    const message = e instanceof Error ? e.message : String(e)
    return NextResponse.json({ error: message }, { status: 500 })
  }
}
