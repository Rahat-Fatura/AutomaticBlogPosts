/*
  Warnings:

  - A unique constraint covering the columns `[wpPostId]` on the table `Announcement` will be added. If there are existing duplicate values, this will fail.

*/
-- AlterTable
ALTER TABLE "Announcement" ADD COLUMN     "wpPostId" INTEGER;

-- CreateIndex
CREATE UNIQUE INDEX "Announcement_wpPostId_key" ON "Announcement"("wpPostId");

-- CreateIndex
CREATE INDEX "Announcement_wpPostId_idx" ON "Announcement"("wpPostId");
