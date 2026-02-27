"use client"

import Link from 'next/link'
import { usePathname } from 'next/navigation'

export default function Sidebar() {
  const pathname = usePathname()
  const isPanel = pathname === '/panel' || pathname.startsWith('/panel/')

  return (
    <aside className="hidden w-64 shrink-0 border-r border-gray-200 bg-white md:block">
      <div className="px-5 py-4">
        <div className="text-sm font-semibold text-gray-900">Blog Admin</div>
        <div className="mt-1 text-xs text-gray-600">Mock Mode</div>
      </div>
      <nav className="px-2 pb-4">
        <Link
          href="/panel"
          className={`block rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 ${
            isPanel ? 'bg-gray-100 text-gray-900 font-medium' : ''
          }`}
        >
          Panel
        </Link>
      </nav>
    </aside>
  )
}
