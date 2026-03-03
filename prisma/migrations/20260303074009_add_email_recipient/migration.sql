/*
  Warnings:

  - A unique constraint covering the columns `[approvalToken]` on the table `Draft` will be added. If there are existing duplicate values, this will fail.

*/
-- AlterTable
ALTER TABLE "Draft" ADD COLUMN     "aiGenerationFailed" BOOLEAN NOT NULL DEFAULT false,
ADD COLUMN     "approvalToken" TEXT,
ADD COLUMN     "tokenExpiresAt" TIMESTAMP(3);

-- CreateTable
CREATE TABLE "EmailRecipient" (
    "id" UUID NOT NULL,
    "name" TEXT NOT NULL,
    "email" TEXT NOT NULL,
    "role" TEXT,
    "isActive" BOOLEAN NOT NULL DEFAULT true,
    "createdAt" TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updatedAt" TIMESTAMP(3) NOT NULL,

    CONSTRAINT "EmailRecipient_pkey" PRIMARY KEY ("id")
);

-- CreateIndex
CREATE UNIQUE INDEX "EmailRecipient_email_key" ON "EmailRecipient"("email");

-- CreateIndex
CREATE INDEX "EmailRecipient_isActive_idx" ON "EmailRecipient"("isActive");

-- CreateIndex
CREATE UNIQUE INDEX "Draft_approvalToken_key" ON "Draft"("approvalToken");

-- CreateIndex
CREATE INDEX "Draft_approvalToken_idx" ON "Draft"("approvalToken");
