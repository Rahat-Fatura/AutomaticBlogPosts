import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { sendEmail, generateTestEmailHTML } from '@/lib/email';

export async function POST() {
  try {
    const activeRecipients = await prisma.emailRecipient.findMany({
      where: { isActive: true },
    });

    if (activeRecipients.length === 0) {
      return NextResponse.json(
        { error: 'No active email recipients found' },
        { status: 400 }
      );
    }

    const emailAddresses = activeRecipients.map(r => r.email);
    const html = generateTestEmailHTML();

    const result = await sendEmail({
      to: emailAddresses,
      subject: '✅ Test Email - Admin Panel',
      html,
    });

    if (result.success) {
      return NextResponse.json({
        success: true,
        message: `Test email sent to ${emailAddresses.length} recipient(s)`,
        recipients: emailAddresses,
      });
    } else {
      return NextResponse.json(
        { error: result.error },
        { status: 500 }
      );
    }
  } catch (error) {
    console.error('Error sending test email:', error);
    return NextResponse.json(
      { error: 'Failed to send test email' },
      { status: 500 }
    );
  }
}
