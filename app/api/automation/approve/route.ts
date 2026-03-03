import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { verifyToken } from '@/lib/token';
import { publishToWordPress } from '@/lib/wordpress';

export async function GET(request: NextRequest) {
  const searchParams = request.nextUrl.searchParams;
  const token = searchParams.get('token');

  if (!token) {
    return NextResponse.redirect(new URL('/automation/error?message=Token bulunamadı', request.url));
  }

  try {
    const payload = verifyToken(token);

    if (!payload || payload.action !== 'approve') {
      return NextResponse.redirect(new URL('/automation/error?message=Geçersiz veya süresi dolmuş token', request.url));
    }

    const draft = await prisma.draft.findUnique({
      where: { id: payload.draftId },
      include: { announcement: true },
    });

    if (!draft) {
      return NextResponse.redirect(new URL('/automation/error?message=Taslak bulunamadı', request.url));
    }

    if (draft.status === 'published') {
      return NextResponse.redirect(new URL('/automation/success?message=Bu taslak zaten yayınlanmış', request.url));
    }

    if (draft.status === 'rejected') {
      return NextResponse.redirect(new URL('/automation/error?message=Bu taslak reddedilmiş', request.url));
    }

    if (draft.approvalToken !== token) {
      return NextResponse.redirect(new URL('/automation/error?message=Token eşleşmiyor', request.url));
    }

    const wpResult = await publishToWordPress({
      title: draft.title,
      content: draft.content,
      excerpt: draft.metaDescription || '',
      status: 'publish',
    });

    if (!wpResult.success) {
      return NextResponse.redirect(new URL(`/automation/error?message=WordPress hatası: ${wpResult.error}`, request.url));
    }

    await prisma.draft.update({
      where: { id: draft.id },
      data: {
        status: 'published',
        publishedAt: new Date(),
        approvalToken: null,
      },
    });

    await prisma.announcement.update({
      where: { id: draft.announcementId },
      data: {
        wpPostId: wpResult.postId,
      },
    });

    return NextResponse.redirect(new URL(`/automation/success?message=Makale başarıyla yayınlandı&title=${encodeURIComponent(draft.title)}`, request.url));
  } catch (error) {
    console.error('Approval error:', error);
    return NextResponse.redirect(new URL('/automation/error?message=Bir hata oluştu', request.url));
  }
}
