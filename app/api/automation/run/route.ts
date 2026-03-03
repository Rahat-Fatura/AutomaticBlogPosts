import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import { generateApprovalToken, generateRejectionToken } from '@/lib/token';
import { sendEmail, generateDraftEmailHTML } from '@/lib/email';
import { callGeminiGenerateContent } from '@/app/api/ai/generate/route';
import { exec } from 'child_process';
import { promisify } from 'util';
import path from 'path';

const execAsync = promisify(exec);

interface ScraperResult {
  source_name: string;
  source_url: string;
  scraped_at: string;
  duyurular: Array<{
    baslik: string;
    tarih: string;
    link: string;
    ozet: string;
  }>;
}

export async function POST() {
  const startTime = Date.now();
  const log: string[] = [];

  try {
    log.push(`[${new Date().toISOString()}] Automation started`);

    // Step 1: Run scraper
    log.push('Step 1: Running scraper...');
    const scraperPath = path.join(process.cwd(), 'scraper', 'scraper.js');
    
    try {
      const { stdout, stderr } = await execAsync(`node "${scraperPath}"`, {
        cwd: path.join(process.cwd(), 'scraper'),
        timeout: 600000, // 10 minutes
      });
      
      if (stderr) {
        log.push(`Scraper warnings: ${stderr.substring(0, 500)}`);
      }
      log.push('Scraper completed successfully');
    } catch (error) {
      log.push(`Scraper error: ${error instanceof Error ? error.message : 'Unknown error'}`);
      throw new Error('Scraper failed');
    }

    // Step 2: Load scraper results
    log.push('Step 2: Loading scraper results...');
    const fs = require('fs');
    const outputPath = path.join(process.cwd(), 'scraper', 'output', 'duyurular.json');
    
    if (!fs.existsSync(outputPath)) {
      throw new Error('Scraper output file not found');
    }

    const scraperData = JSON.parse(fs.readFileSync(outputPath, 'utf-8')) as {
      sources: ScraperResult[];
    };

    log.push(`Found ${scraperData.sources.length} sources`);

    // Step 3: Detect new announcements
    log.push('Step 3: Detecting new announcements...');
    const newAnnouncements: Array<{
      title: string;
      content: string;
      url: string;
      source: string;
    }> = [];

    for (const source of scraperData.sources) {
      for (const duyuru of source.duyurular) {
        const exists = await prisma.announcement.findUnique({
          where: { originalUrl: duyuru.link },
        });

        if (!exists && duyuru.ozet && duyuru.ozet.length > 50) {
          newAnnouncements.push({
            title: duyuru.baslik,
            content: duyuru.ozet,
            url: duyuru.link,
            source: source.source_name,
          });
        }
      }
    }

    log.push(`Found ${newAnnouncements.length} new announcements`);

    if (newAnnouncements.length === 0) {
      return NextResponse.json({
        success: true,
        message: 'No new announcements found',
        log,
        duration: Date.now() - startTime,
      });
    }

    // Step 4: Generate AI content and create drafts
    log.push('Step 4: Generating AI content...');
    const draftsCreated: Array<{
      id: string;
      title: string;
      content: string;
      approvalToken: string;
      createdAt: Date;
    }> = [];

    for (const announcement of newAnnouncements.slice(0, 3)) { // Limit to 3 per run to avoid rate limits
      try {
        // Add delay to avoid rate limits (3 seconds between requests)
        if (draftsCreated.length > 0) {
          await new Promise(resolve => setTimeout(resolve, 3000));
        }
        
        // Generate AI content
        const prompt = `Aşağıdaki resmi duyuruyu SEO uyumlu, profesyonel bir blog yazısına dönüştür.

DUYURU BAŞLIĞI: ${announcement.title}

DUYURU İÇERİĞİ: ${announcement.content}

ÖNEMLI: Sadece aşağıdaki JSON formatında yanıt ver, başka hiçbir şey yazma:
{
  "title": "Blog yazısı başlığı (SEO uyumlu, çekici)",
  "content": "Blog yazısı içeriği (HTML formatında, <p>, <h2>, <ul>, <li> etiketleri kullan, en az 300 kelime)",
  "metaDescription": "Meta açıklama (150-160 karakter)"
}`
        
        const apiKey = process.env.GEMINI_API_KEY || '';
        const model = process.env.GEMINI_MODEL || 'gemini-2.5-flash';
        
        const aiResult = await callGeminiGenerateContent({
          apiKey,
          model,
          prompt,
        });

        // Parse AI response (expecting JSON format)
        let aiData: { title?: string; content?: string; metaDescription?: string } = {
          title: announcement.title,
          content: announcement.content,
          metaDescription: undefined,
        };

        if (!aiResult.ok) {
          log.push(`AI generation failed for: ${announcement.title} - using original content`);
        } else {
          let aiText = aiResult.text;
          
          // Remove markdown code blocks if present (```json ... ```)
          aiText = aiText.replace(/```json\s*/gi, '').replace(/```/g, '').trim();
          
          // Try to parse JSON from AI response
          try {
            const jsonMatch = aiText.match(/\{[\s\S]*\}/);
            if (jsonMatch) {
              const parsedData = JSON.parse(jsonMatch[0]);
              if (parsedData.title && parsedData.content) {
                aiData = parsedData;
                log.push(`AI content generated for: ${announcement.title}`);
              } else {
                log.push(`AI data incomplete for: ${announcement.title}, using original content`);
              }
            } else {
              log.push(`No JSON found in AI response for: ${announcement.title}, using original content`);
            }
          } catch (e) {
            log.push(`Failed to parse AI JSON for: ${announcement.title}, using original content`);
          }
        }

        // Create announcement in DB
        const dbAnnouncement = await prisma.announcement.create({
          data: {
            title: announcement.title,
            content: announcement.content,
            originalUrl: announcement.url,
            contentHash: Buffer.from(announcement.content).toString('base64').substring(0, 64),
            fetchedAt: new Date(),
            aiStatus: 'done',
            aiTitle: aiData.title || announcement.title,
            aiContent: aiData.content || announcement.content,
            aiExcerpt: null,
            aiMetaDescription: aiData.metaDescription || null,
            source: {
              connectOrCreate: {
                where: { url: announcement.source },
                create: {
                  name: announcement.source,
                  url: announcement.source,
                  isActive: true,
                },
              },
            },
          },
        });

        // Create draft first (without token)
        const draft = await prisma.draft.create({
          data: {
            announcementId: dbAnnouncement.id,
            title: aiData.title || announcement.title,
            content: aiData.content || announcement.content,
            metaDescription: aiData.metaDescription || null,
            status: 'pending',
            aiGenerationFailed: false,
            tokenExpiresAt: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000),
          },
        });

        // Generate tokens with draft ID
        const approvalToken = generateApprovalToken(draft.id);

        // Update draft with token
        await prisma.draft.update({
          where: { id: draft.id },
          data: { approvalToken },
        });

        draftsCreated.push({
          id: draft.id,
          title: draft.title,
          content: draft.content,
          approvalToken,
          createdAt: draft.createdAt,
        });

        log.push(`Created draft: ${draft.title}`);
      } catch (error) {
        log.push(`Error processing ${announcement.title}: ${error instanceof Error ? error.message : 'Unknown'}`);
      }
    }

    log.push(`Created ${draftsCreated.length} drafts`);

    if (draftsCreated.length === 0) {
      return NextResponse.json({
        success: true,
        message: 'New announcements found but AI generation failed',
        log,
        duration: Date.now() - startTime,
      });
    }

    // Step 5: Send email notification
    log.push('Step 5: Sending email notification...');
    const activeRecipients = await prisma.emailRecipient.findMany({
      where: { isActive: true },
    });

    if (activeRecipients.length === 0) {
      log.push('No active email recipients found');
      return NextResponse.json({
        success: true,
        message: `Created ${draftsCreated.length} drafts but no email sent (no recipients)`,
        drafts: draftsCreated.length,
        log,
        duration: Date.now() - startTime,
      });
    }

    const emailHtml = generateDraftEmailHTML(draftsCreated);
    const emailResult = await sendEmail({
      to: activeRecipients.map(r => r.email),
      subject: `🆕 ${draftsCreated.length} Yeni Makale Taslağı Hazır`,
      html: emailHtml,
    });

    if (emailResult.success) {
      log.push(`Email sent to ${activeRecipients.length} recipient(s)`);
    } else {
      log.push(`Email failed: ${emailResult.error}`);
    }

    return NextResponse.json({
      success: true,
      message: `Automation completed successfully`,
      newAnnouncements: newAnnouncements.length,
      draftsCreated: draftsCreated.length,
      emailSent: emailResult.success,
      recipients: activeRecipients.length,
      log,
      duration: Date.now() - startTime,
    });

  } catch (error) {
    log.push(`Fatal error: ${error instanceof Error ? error.message : 'Unknown error'}`);
    console.error('Automation error:', error);
    
    return NextResponse.json({
      success: false,
      error: error instanceof Error ? error.message : 'Unknown error',
      log,
      duration: Date.now() - startTime,
    }, { status: 500 });
  }
}
