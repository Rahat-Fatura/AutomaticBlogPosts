import { NextResponse } from 'next/server'
import { prisma } from '@/lib/prisma'

export async function GET(req: Request) {
  try {
    const { searchParams } = new URL(req.url)
    const wpPostId = searchParams.get('wpPostId')

    if (!wpPostId) {
      return NextResponse.json({ error: 'wpPostId required.' }, { status: 400 })
    }

    const announcement = await prisma.announcement.findUnique({
      where: { wpPostId: Number(wpPostId) } as any,
      select: { originalUrl: true, content: true },
    })

    if (!announcement) {
      return NextResponse.json({ error: 'Announcement not found.' }, { status: 404 })
    }

    return NextResponse.json({ 
      originalUrl: announcement.originalUrl,
      content: announcement.content 
    })
  } catch (e) {
    const message = e instanceof Error ? e.message : String(e)
    return NextResponse.json({ error: message }, { status: 500 })
  }
}
