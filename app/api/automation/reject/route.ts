import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { verifyToken } from '@/lib/token';

export async function GET(request: NextRequest) {
  const searchParams = request.nextUrl.searchParams;
  const token = searchParams.get('token');

  if (!token) {
    return NextResponse.redirect(new URL('/automation/error?message=Token bulunamadı', request.url));
  }

  try {
    const payload = verifyToken(token);

    if (!payload || payload.action !== 'reject') {
      return NextResponse.redirect(new URL('/automation/error?message=Geçersiz veya süresi dolmuş token', request.url));
    }

    const draft = await prisma.draft.findUnique({
      where: { id: payload.draftId },
    });

    if (!draft) {
      return NextResponse.redirect(new URL('/automation/error?message=Taslak bulunamadı', request.url));
    }

    if (draft.status === 'rejected') {
      return NextResponse.redirect(new URL('/automation/success?message=Bu taslak zaten reddedilmiş', request.url));
    }

    if (draft.status === 'published') {
      return NextResponse.redirect(new URL('/automation/error?message=Yayınlanmış taslak reddedilemez', request.url));
    }

    await prisma.draft.update({
      where: { id: draft.id },
      data: {
        status: 'rejected',
        approvalToken: null,
      },
    });

    return NextResponse.redirect(new URL(`/automation/success?message=Taslak reddedildi&title=${encodeURIComponent(draft.title)}`, request.url));
  } catch (error) {
    console.error('Rejection error:', error);
    return NextResponse.redirect(new URL('/automation/error?message=Bir hata oluştu', request.url));
  }
}
