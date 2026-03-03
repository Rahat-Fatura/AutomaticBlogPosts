-- CreateEnum
CREATE TYPE "AnnouncementStatus" AS ENUM ('new', 'filtered', 'processed', 'rejected');

-- CreateEnum
CREATE TYPE "DraftStatus" AS ENUM ('pending', 'approved', 'published', 'rejected');

-- CreateTable
CREATE TABLE "Source" (
    "id" UUID NOT NULL,
    "name" TEXT NOT NULL,
    "url" TEXT NOT NULL,
    "isActive" BOOLEAN NOT NULL DEFAULT true,
    "checkIntervalHours" INTEGER NOT NULL DEFAULT 6,
    "createdAt" TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT "Source_pkey" PRIMARY KEY ("id")
);

-- CreateTable
CREATE TABLE "Announcement" (
    "id" UUID NOT NULL,
    "sourceId" UUID NOT NULL,
    "title" TEXT NOT NULL,
    "content" TEXT NOT NULL,
    "originalUrl" TEXT NOT NULL,
    "contentHash" TEXT NOT NULL,
    "status" "AnnouncementStatus" NOT NULL DEFAULT 'new',
    "fetchedAt" TIMESTAMP(3) NOT NULL,
    "createdAt" TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT "Announcement_pkey" PRIMARY KEY ("id")
);

-- CreateTable
CREATE TABLE "Draft" (
    "id" UUID NOT NULL,
    "announcementId" UUID NOT NULL,
    "title" TEXT NOT NULL,
    "content" TEXT NOT NULL,
    "metaDescription" TEXT,
    "status" "DraftStatus" NOT NULL DEFAULT 'pending',
    "createdAt" TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "publishedAt" TIMESTAMP(3),

    CONSTRAINT "Draft_pkey" PRIMARY KEY ("id")
);

-- CreateIndex
CREATE UNIQUE INDEX "Source_url_key" ON "Source"("url");

-- CreateIndex
CREATE UNIQUE INDEX "Announcement_originalUrl_key" ON "Announcement"("originalUrl");

-- CreateIndex
CREATE UNIQUE INDEX "Announcement_contentHash_key" ON "Announcement"("contentHash");

-- CreateIndex
CREATE INDEX "Announcement_sourceId_idx" ON "Announcement"("sourceId");

-- CreateIndex
CREATE INDEX "Announcement_status_idx" ON "Announcement"("status");

-- CreateIndex
CREATE UNIQUE INDEX "Draft_announcementId_key" ON "Draft"("announcementId");

-- CreateIndex
CREATE INDEX "Draft_status_idx" ON "Draft"("status");

-- AddForeignKey
ALTER TABLE "Announcement" ADD CONSTRAINT "Announcement_sourceId_fkey" FOREIGN KEY ("sourceId") REFERENCES "Source"("id") ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "Draft" ADD CONSTRAINT "Draft_announcementId_fkey" FOREIGN KEY ("announcementId") REFERENCES "Announcement"("id") ON DELETE CASCADE ON UPDATE CASCADE;
