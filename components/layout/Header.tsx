'use client'

import { usePathname } from 'next/navigation'
import { useRouter } from 'next/navigation'

function getTitle(pathname: string) {
  if (pathname === '/posts') return 'Blog Yazıları'
  if (pathname === '/posts/new') return 'Yeni Yazı'
  if (pathname.startsWith('/posts/')) return 'Yazıyı Düzenle'
  return 'Blog Admin'
}

export default function Header() {
  const pathname = usePathname()
  const router = useRouter()
  const title = getTitle(pathname)
  const useMock = process.env.NEXT_PUBLIC_USE_MOCK === 'true'

  async function logout() {
    await fetch('/api/auth/logout', { method: 'POST' })
    router.replace('/login')
  }

  return (
    <header className="sticky top-0 z-20 border-b border-gray-200 bg-white backdrop-blur">
      <div className="flex h-14 items-center justify-between px-6">
        <div className="min-w-0">
          <div className="truncate text-sm font-medium text-gray-900">{title}</div>
        </div>
        <div className="flex items-center gap-2">
          <span className="rounded-full border border-gray-300 bg-gray-100 px-2 py-0.5 text-xs text-gray-700">
            {useMock ? 'Mock Mode' : 'Live'}
          </span>
          <button
            type="button"
            onClick={() => void logout()}
            className="rounded-lg bg-gray-200 px-3 py-1.5 text-xs font-medium text-gray-900 hover:bg-gray-300"
          >
            Çıkış
          </button>
        </div>
      </div>
    </header>
  )
}
