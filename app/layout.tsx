import type { Metadata } from "next";
import { Geist, Geist_Mono } from "next/font/google";
import "./globals.css";
import Header from "@/components/layout/Header";
import { ToastProvider } from "@/components/ui/Toast";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "Blog Admin",
  description: "WordPress bağımsız blog admin paneli (mock mode)",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="tr">
      <body className={`${geistSans.variable} ${geistMono.variable} antialiased bg-gray-50 text-gray-900`}>
        <ToastProvider>
          <div className="flex min-h-dvh flex-col">
            <Header />
            <main className="flex-1 overflow-y-auto bg-gray-50 p-6">{children}</main>
          </div>
        </ToastProvider>
      </body>
    </html>
  );
}
