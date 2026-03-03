import { NextRequest, NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function GET() {
  try {
    const recipients = await prisma.emailRecipient.findMany({
      orderBy: { createdAt: 'desc' },
    });

    return NextResponse.json(recipients);
  } catch (error) {
    console.error('Error fetching email recipients:', error);
    return NextResponse.json(
      { error: 'Failed to fetch email recipients' },
      { status: 500 }
    );
  }
}

export async function POST(request: NextRequest) {
  try {
    const body = await request.json();
    const { name, email, role } = body;

    if (!name || !email) {
      return NextResponse.json(
        { error: 'Name and email are required' },
        { status: 400 }
      );
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return NextResponse.json(
        { error: 'Invalid email format' },
        { status: 400 }
      );
    }

    const existingRecipient = await prisma.emailRecipient.findUnique({
      where: { email },
    });

    if (existingRecipient) {
      return NextResponse.json(
        { error: 'Email already exists' },
        { status: 409 }
      );
    }

    const recipient = await prisma.emailRecipient.create({
      data: {
        name,
        email,
        role: role || null,
        isActive: true,
      },
    });

    return NextResponse.json(recipient, { status: 201 });
  } catch (error) {
    console.error('Error creating email recipient:', error);
    return NextResponse.json(
      { error: 'Failed to create email recipient' },
      { status: 500 }
    );
  }
}
