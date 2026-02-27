import { NextResponse } from 'next/server'
import { prisma } from '@/lib/prisma'

export async function POST(req: Request) {
  try {
    const body = (await req.json().catch(() => null)) as null | { originalUrl?: unknown; wpPostId?: unknown }
    const originalUrl = typeof body?.originalUrl === 'string' ? body.originalUrl.trim() : ''
    const wpPostId = typeof body?.wpPostId === 'number' ? body.wpPostId : null

    if (!originalUrl || !wpPostId) {
      return NextResponse.json({ error: 'originalUrl and wpPostId required.' }, { status: 400 })
    }

    const announcement = await prisma.announcement.findUnique({
      where: { originalUrl },
    })

    if (!announcement) {
      return NextResponse.json({ error: 'Announcement not found.' }, { status: 404 })
    }

    await prisma.announcement.update({
      where: { id: announcement.id },
      data: { wpPostId } as any,
    })

    return NextResponse.json({ success: true })
  } catch (e) {
    const message = e instanceof Error ? e.message : String(e)
    return NextResponse.json({ error: message }, { status: 500 })
  }
}
