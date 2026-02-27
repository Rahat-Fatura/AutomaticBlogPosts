import { NextResponse } from 'next/server'
import { prisma } from '@/lib/prisma'

export async function GET(req: Request) {
  try {
    const { searchParams } = new URL(req.url)
    const originalUrl = searchParams.get('originalUrl')

    if (!originalUrl) {
      return NextResponse.json({ error: 'originalUrl gerekli.' }, { status: 400 })
    }

    const announcement = await prisma.announcement.findUnique({
      where: { originalUrl },
      select: { content: true },
    })

    if (!announcement) {
      return NextResponse.json({ error: 'Duyuru bulunamadı.' }, { status: 404 })
    }

    return NextResponse.json({ content: announcement.content })
  } catch (e) {
    const message = e instanceof Error ? e.message : String(e)
    return NextResponse.json({ error: message }, { status: 500 })
  }
}
