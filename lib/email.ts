import nodemailer from 'nodemailer';

const transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: process.env.GMAIL_USER,
    pass: process.env.GMAIL_APP_PASSWORD,
  },
});

export interface EmailOptions {
  to: string | string[];
  subject: string;
  html: string;
}

export async function sendEmail(options: EmailOptions) {
  try {
    const info = await transporter.sendMail({
      from: `"Admin Panel" <${process.env.GMAIL_USER}>`,
      to: Array.isArray(options.to) ? options.to.join(', ') : options.to,
      subject: options.subject,
      html: options.html,
    });

    console.log('Email sent:', info.messageId);
    return { success: true, messageId: info.messageId };
  } catch (error) {
    console.error('Email send error:', error);
    return { success: false, error: error instanceof Error ? error.message : 'Unknown error' };
  }
}

export function generateDraftEmailHTML(drafts: Array<{
  id: string;
  title: string;
  content: string;
  approvalToken: string;
  createdAt: Date;
}>) {
  const baseUrl = process.env.NEXTAUTH_URL || 'http://localhost:3000';
  
  const draftItems = drafts.map(draft => `
    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-bottom: 20px; background: white;">
      <h2 style="margin: 0 0 10px 0; color: #111827; font-size: 20px;">
        ${draft.title}
      </h2>
      <p style="color: #6b7280; font-size: 14px; margin: 0 0 15px 0;">
        📅 ${new Date(draft.createdAt).toLocaleString('tr-TR')}
      </p>
      
      <div style="background: #f9fafb; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
        <div style="color: #374151; line-height: 1.6; max-height: 300px; overflow: hidden;">
          ${draft.content.substring(0, 1000)}${draft.content.length > 1000 ? '...' : ''}
        </div>
      </div>
      
      <div style="display: flex; gap: 10px; margin-top: 15px;">
        <a href="${baseUrl}/api/automation/approve?token=${draft.approvalToken}" 
           style="background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block;">
          ✓ Onayla ve Yayınla
        </a>
        <a href="${baseUrl}/panel?tab=drafts&draft=${draft.id}" 
           style="background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block;">
          ✏️ Panelde Düzenle
        </a>
        <a href="${baseUrl}/api/automation/reject?token=${draft.approvalToken}" 
           style="background: #ef4444; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block;">
          ✗ Reddet
        </a>
      </div>
    </div>
  `).join('');

  return `
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6;">
      <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <h1 style="margin: 0 0 10px 0; color: #111827; font-size: 24px;">
            🆕 Yeni Taslaklar Hazır
          </h1>
          <p style="color: #6b7280; margin: 0 0 30px 0;">
            ${drafts.length} adet yeni makale taslağı AI tarafından oluşturuldu ve onayınızı bekliyor.
          </p>
          
          ${draftItems}
          
          <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
            <p style="margin: 0;">
              Bu email otomatik olarak gönderilmiştir. Taslakları onaylamak veya düzenlemek için yukarıdaki butonları kullanabilirsiniz.
            </p>
          </div>
        </div>
      </div>
    </body>
    </html>
  `;
}

export function generateTestEmailHTML() {
  const baseUrl = process.env.NEXTAUTH_URL || 'http://localhost:3000';
  
  return `
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6;">
      <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
          <h1 style="margin: 0 0 10px 0; color: #111827; font-size: 24px;">
            ✅ Test Email
          </h1>
          <p style="color: #6b7280; margin: 0 0 20px 0;">
            Email sistemi başarıyla çalışıyor! Bu bir test mesajıdır.
          </p>
          
          <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; background: #f9fafb;">
            <h2 style="margin: 0 0 10px 0; color: #111827; font-size: 18px;">
              Örnek Makale Başlığı
            </h2>
            <p style="color: #6b7280; font-size: 14px; margin: 0 0 15px 0;">
              📅 ${new Date().toLocaleString('tr-TR')}
            </p>
            <p style="color: #374151; line-height: 1.6;">
              Bu bir örnek makale içeriğidir. Gerçek taslaklar oluşturulduğunda, tam içerik burada görünecektir.
            </p>
          </div>
          
          <div style="margin-top: 20px; padding: 15px; background: #dbeafe; border-radius: 6px;">
            <p style="margin: 0; color: #1e40af; font-size: 14px;">
              💡 <strong>Not:</strong> Gerçek taslak email'lerinde "Onayla", "Düzenle" ve "Reddet" butonları olacaktır.
            </p>
          </div>
          
          <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <a href="${baseUrl}/panel" 
               style="background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block;">
              Admin Panele Git
            </a>
          </div>
        </div>
      </div>
    </body>
    </html>
  `;
}
