import { NextResponse } from 'next/server';
import { getCronStatus } from '@/lib/cron';

export async function GET() {
  try {
    const status = getCronStatus();
    
    return NextResponse.json({
      success: true,
      status,
    });
  } catch (error) {
    return NextResponse.json({
      success: false,
      error: error instanceof Error ? error.message : 'Unknown error',
    }, { status: 500 });
  }
}
