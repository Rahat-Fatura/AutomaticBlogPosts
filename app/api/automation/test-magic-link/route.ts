import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { generateApprovalToken, generateRejectionToken } from '@/lib/token';

export async function GET() {
  try {
    // Create a test draft
    const testDraft = await prisma.draft.create({
      data: {
        title: 'Test Magic Link Makalesi',
        content: '<p>Bu bir test makalesidir. Magic link sistemini test etmek için oluşturulmuştur.</p>',
        metaDescription: 'Test magic link',
        status: 'pending',
        aiGenerationFailed: false,
        announcement: {
          create: {
            title: 'Test Duyuru',
            content: 'Test içerik',
            originalUrl: 'https://test.com/test-' + Date.now(),
            contentHash: 'test-hash-' + Date.now(),
            fetchedAt: new Date(),
            source: {
              connectOrCreate: {
                where: { url: 'https://test.com' },
                create: {
                  name: 'Test Source',
                  url: 'https://test.com',
                  isActive: true,
                },
              },
            },
          },
        },
      },
    });

    // Generate tokens
    const approvalToken = generateApprovalToken(testDraft.id);
    const rejectionToken = generateRejectionToken(testDraft.id);

    // Update draft with approval token
    await prisma.draft.update({
      where: { id: testDraft.id },
      data: {
        approvalToken,
        tokenExpiresAt: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000), // 7 days
      },
    });

    const baseUrl = process.env.NEXTAUTH_URL || 'http://localhost:3000';

    return NextResponse.json({
      success: true,
      message: 'Test taslağı oluşturuldu',
      draftId: testDraft.id,
      links: {
        approve: `${baseUrl}/api/automation/approve?token=${approvalToken}`,
        reject: `${baseUrl}/api/automation/reject?token=${rejectionToken}`,
        panel: `${baseUrl}/panel`,
      },
      instructions: [
        '1. "approve" linkine tıkla - Taslak WordPress\'e yayınlanacak',
        '2. "reject" linkine tıkla - Taslak reddedilecek',
        '3. Panel\'den taslağı görebilirsin',
      ],
    });
  } catch (error) {
    console.error('Test magic link error:', error);
    return NextResponse.json(
      { error: 'Test oluşturulamadı: ' + (error instanceof Error ? error.message : 'Bilinmeyen hata') },
      { status: 500 }
    );
  }
}
