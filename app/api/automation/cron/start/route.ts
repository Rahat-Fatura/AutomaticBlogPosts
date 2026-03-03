import { NextResponse } from 'next/server';
import { startAutomationCron, getCronStatus } from '@/lib/cron';

export async function POST() {
  try {
    startAutomationCron();
    const status = getCronStatus();
    
    return NextResponse.json({
      success: true,
      message: 'Cron job started',
      status,
    });
  } catch (error) {
    return NextResponse.json({
      success: false,
      error: error instanceof Error ? error.message : 'Unknown error',
    }, { status: 500 });
  }
}
