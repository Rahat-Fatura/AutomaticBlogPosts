import cron, { ScheduledTask } from 'node-cron';

let cronJob: ScheduledTask | null = null;

export function startAutomationCron() {
  if (cronJob) {
    console.log('Cron job already running');
    return;
  }

  // Default: Every 6 hours
  const schedule = process.env.CRON_SCHEDULE || '0 */6 * * *';
  
  console.log(`Starting automation cron with schedule: ${schedule}`);

  cronJob = cron.schedule(schedule, async () => {
    console.log(`[${new Date().toISOString()}] Running scheduled automation...`);
    
    try {
      const baseUrl = process.env.NEXTAUTH_URL || 'http://localhost:3000';
      const response = await fetch(`${baseUrl}/api/automation/run`, {
        method: 'POST',
      });

      const result = await response.json();
      
      if (result.success) {
        console.log(`Automation completed: ${result.draftsCreated || 0} drafts created`);
      } else {
        console.error('Automation failed:', result.error);
      }
    } catch (error) {
      console.error('Cron job error:', error);
    }
  });

  console.log('Automation cron job started successfully');
}

export function stopAutomationCron() {
  if (cronJob) {
    cronJob.stop();
    cronJob = null;
    console.log('Automation cron job stopped');
  }
}

export function getCronStatus() {
  return {
    running: cronJob !== null,
    schedule: process.env.CRON_SCHEDULE || '0 */6 * * *',
  };
}
