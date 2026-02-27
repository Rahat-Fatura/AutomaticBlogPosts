'use client'

import { useEffect, useMemo, useState } from 'react'
import PostsPanel from '@/components/panel/PostsPanel'
import ScraperPanel from '@/components/panel/ScraperPanel'

type TabKey = 'posts' | 'scraper'

const TAB_KEY = 'panel_tab'

export default function PanelPage() {
  const [tab, setTab] = useState<TabKey>('scraper')

  useEffect(() => {
    const saved = (typeof window !== 'undefined' ? window.localStorage.getItem(TAB_KEY) : null) as TabKey | null
    if (saved === 'posts' || saved === 'scraper') {
      setTab(saved)
    }
  }, [])

  useEffect(() => {
    if (typeof window === 'undefined') return
    window.localStorage.setItem(TAB_KEY, tab)
  }, [tab])

  const content = useMemo(() => {
    if (tab === 'scraper') return <ScraperPanel />
    return <PostsPanel />
  }, [tab])

  const tabBtn = (key: TabKey, label: string) => (
    <button
      type="button"
      onClick={() => setTab(key)}
      className={`rounded-lg px-4 py-2 text-sm font-medium transition-colors ${
        tab === key ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300'
      }`}
    >
      {label}
    </button>
  )

  return (
    <div className="mx-auto w-full max-w-6xl">
      <div className="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
          <div className="text-lg font-semibold text-gray-900">Panel</div>
          <div className="mt-1 text-xs text-gray-600">WordPress yazıları ve scraper yönetimi.</div>
        </div>

        <div className="flex flex-wrap items-center gap-2">
          {tabBtn('posts', 'Yazılar')}
          {tabBtn('scraper', 'Scraper')}
        </div>
      </div>

      {content}
    </div>
  )
}
