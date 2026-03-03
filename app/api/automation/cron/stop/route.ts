import { NextResponse } from 'next/server';
import { stopAutomationCron, getCronStatus } from '@/lib/cron';

export async function POST() {
  try {
    stopAutomationCron();
    const status = getCronStatus();
    
    return NextResponse.json({
      success: true,
      message: 'Cron job stopped',
      status,
    });
  } catch (error) {
    return NextResponse.json({
      success: false,
      error: error instanceof Error ? error.message : 'Unknown error',
    }, { status: 500 });
  }
}
