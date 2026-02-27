# Admin Panel - Duyuru Yönetim Sistemi

Next.js tabanlı admin paneli ve otomatik duyuru scraper sistemi.

## 🚀 Özellikler

- **5 Farklı Kaynaktan Otomatik Duyuru Çekme**
  - ebelge.gib.gov.tr
  - dijital.gib.gov.tr
  - ticaret.gov.tr
  - ggm.ticaret.gov.tr
  - gib.gov.tr
- **WordPress Entegrasyonu** - Duyuruları otomatik WordPress'e gönderme
- **Prisma ORM** - PostgreSQL veritabanı yönetimi
- **NextAuth** - Güvenli admin girişi
- **Modern UI** - Tailwind CSS ile responsive tasarım

## 📋 Gereksinimler

- Node.js 18+
- PostgreSQL 14+
- Docker (opsiyonel)

## 🛠️ Kurulum

1. **Projeyi klonlayın**
```bash
git clone https://github.com/Rahat-Fatura/admin_panel.git
cd admin_panel
```

2. **Bağımlılıkları yükleyin**
```bash
npm install
```

3. **Ortam değişkenlerini ayarlayın**
```bash
cp .env.example .env.local
```

`.env.local` dosyasını düzenleyip kendi bilgilerinizi girin:
- `DATABASE_URL`: PostgreSQL bağlantı URL'i
- `WORDPRESS_URL`: WordPress site URL'i
- `WORDPRESS_USERNAME`: WordPress kullanıcı adı
- `WORDPRESS_PASSWORD`: WordPress uygulama şifresi
- `NEXTAUTH_SECRET`: NextAuth için güvenlik anahtarı
- `ADMIN_USERNAME`: Admin panel kullanıcı adı
- `ADMIN_PASSWORD`: Admin panel şifresi

4. **Veritabanını başlatın**
```bash
# Docker ile PostgreSQL (önerilen)
docker-compose up -d

# Prisma migration
npx prisma db push
```

5. **Geliştirme sunucusunu başlatın**
```bash
npm run dev
```

Tarayıcınızda [http://localhost:3000](http://localhost:3000) adresini açın.

## 📦 Scraper Kullanımı

Scraper'ı manuel olarak çalıştırmak için:

```bash
node scraper/scraper.js
```

Veya admin panelden "Şimdi Çalıştır" butonuna tıklayın.

## 🗂️ Proje Yapısı

```
├── app/                  # Next.js app directory
├── components/           # React bileşenleri
├── lib/                  # Yardımcı fonksiyonlar
├── prisma/              # Prisma schema ve migrations
├── scraper/             # Duyuru scraper'ları
│   ├── sites/           # Site-specific scraper'lar
│   └── scraper.js       # Ana scraper
└── public/              # Statik dosyalar
```

## 🔧 Teknolojiler

- **Framework**: Next.js 15 (App Router)
- **Database**: PostgreSQL + Prisma ORM
- **Auth**: NextAuth.js
- **Styling**: Tailwind CSS
- **Scraping**: Puppeteer
- **UI Components**: Radix UI

## 📝 Lisans

MIT
