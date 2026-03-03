import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function PUT(
  request: NextRequest,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    const body = await request.json();
    const { name, email, role, isActive } = body;

    const recipient = await prisma.emailRecipient.update({
      where: { id },
      data: {
        ...(name !== undefined && { name }),
        ...(email !== undefined && { email }),
        ...(role !== undefined && { role }),
        ...(isActive !== undefined && { isActive }),
      },
    });

    return NextResponse.json(recipient);
  } catch (error) {
    console.error('Error updating email recipient:', error);
    return NextResponse.json(
      { error: 'Failed to update email recipient' },
      { status: 500 }
    );
  }
}

export async function DELETE(
  request: NextRequest,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    const activeCount = await prisma.emailRecipient.count({
      where: { isActive: true },
    });

    if (activeCount <= 1) {
      const recipient = await prisma.emailRecipient.findUnique({
        where: { id },
      });

      if (recipient?.isActive) {
        return NextResponse.json(
          { error: 'Son aktif alıcıyı silemezsiniz. Önce başka bir aktif alıcı ekleyin veya bu alıcıyı pasif yapın.' },
          { status: 400 }
        );
      }
    }

    await prisma.emailRecipient.delete({
      where: { id },
    });

    return NextResponse.json({ success: true });
  } catch (error) {
    console.error('Error deleting email recipient:', error);
    return NextResponse.json(
      { error: 'Failed to delete email recipient' },
      { status: 500 }
    );
  }
}
