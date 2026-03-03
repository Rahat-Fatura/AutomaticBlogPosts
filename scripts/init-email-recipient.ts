import { prisma } from '../lib/prisma';

async function main() {
  const email = process.argv[2];
  const name = process.argv[3] || 'Admin';

  if (!email) {
    console.error('Usage: npm run init-email <email> [name]');
    console.error('Example: npm run init-email admin@example.com "Admin User"');
    process.exit(1);
  }

  try {
    const existing = await prisma.emailRecipient.findUnique({
      where: { email },
    });

    if (existing) {
      console.log('✅ Email recipient already exists:', existing);
      return;
    }

    const recipient = await prisma.emailRecipient.create({
      data: {
        name,
        email,
        isActive: true,
      },
    });

    console.log('✅ Email recipient created successfully:');
    console.log('   Name:', recipient.name);
    console.log('   Email:', recipient.email);
    console.log('   Active:', recipient.isActive);
  } catch (error) {
    console.error('❌ Error creating email recipient:', error);
    process.exit(1);
  } finally {
    await prisma.$disconnect();
  }
}

main();
