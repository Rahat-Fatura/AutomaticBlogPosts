-- CreateEnum
CREATE TYPE "AiGenerationStatus" AS ENUM ('none', 'queued', 'running', 'done', 'error');

-- AlterTable
ALTER TABLE "Announcement" ADD COLUMN     "aiContent" TEXT,
ADD COLUMN     "aiError" TEXT,
ADD COLUMN     "aiExcerpt" TEXT,
ADD COLUMN     "aiMetaDescription" TEXT,
ADD COLUMN     "aiStatus" "AiGenerationStatus" NOT NULL DEFAULT 'none',
ADD COLUMN     "aiTitle" TEXT,
ADD COLUMN     "aiUpdatedAt" TIMESTAMP(3);

-- CreateIndex
CREATE INDEX "Announcement_aiStatus_idx" ON "Announcement"("aiStatus");
