'use client'

import { useEffect, useMemo, useState } from 'react'
import Link from 'next/link'
import { useToast } from '@/components/ui/Toast'
import type { WPPost } from '@/lib/types'

export default function DraftsPanel() {
  const toast = useToast()
  const [loading, setLoading] = useState(true)
  const [posts, setPosts] = useState<WPPost[]>([])

  async function load() {
    setLoading(true)
    try {
      const res = await fetch('/api/posts', { cache: 'no-store' })
      if (!res.ok) throw new Error('load failed')
      const json = (await res.json()) as WPPost[]
      setPosts(Array.isArray(json) ? json : [])
    } catch {
      toast.push({ variant: 'error', message: 'WordPress taslakları yüklenemedi.' })
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    void load()
  }, [])

  const drafts = useMemo(() => posts.filter((p) => p.status === 'draft').sort((a, b) => (a.date < b.date ? 1 : -1)), [posts])

  return (
    <div className="mx-auto w-full max-w-6xl">
      <div className="mb-4 flex items-start justify-between gap-3">
        <div>
          <div className="text-lg font-semibold text-slate-100">Taslaklar</div>
          <div className="mt-1 text-xs text-slate-400">WordPress taslakları: {drafts.length}</div>
        </div>
        <Link
          href="/posts/new"
          className="rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-slate-100 hover:bg-slate-600"
        >
          + Yeni Yazı
        </Link>
      </div>

      <div className="rounded-xl border border-slate-700/50 bg-slate-800">
        <div className="border-b border-slate-700/50 px-4 py-3">
          <div className="text-sm font-medium text-slate-100">Liste</div>
        </div>

        <div className="overflow-x-auto">
          <table className="min-w-full text-left text-sm">
            <thead className="bg-slate-900/40 text-xs text-slate-300">
              <tr>
                <th className="px-4 py-3">Başlık</th>
                <th className="px-4 py-3">Tarih</th>
                <th className="px-4 py-3">İşlem</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-700/50">
              {drafts.map((p) => (
                <tr key={p.id} className="text-slate-200">
                  <td className="px-4 py-3">
                    <div className="font-medium text-slate-100">{p.title?.rendered || '(Başlıksız)'}</div>
                    <div className="mt-1 block text-xs text-slate-400">/{p.slug}</div>
                  </td>
                  <td className="px-4 py-3 text-slate-300">{new Date(p.date).toLocaleString()}</td>
                  <td className="px-4 py-3">
                    <Link
                      href={`/posts/${encodeURIComponent(String(p.id))}`}
                      className="rounded-lg bg-slate-700 px-3 py-2 text-xs font-medium text-slate-100 hover:bg-slate-600"
                    >
                      Düzenle
                    </Link>
                  </td>
                </tr>
              ))}

              {!loading && drafts.length === 0 && (
                <tr>
                  <td colSpan={5} className="px-4 py-8 text-center text-sm text-slate-400">
                    WordPress'te taslak yok.
                  </td>
                </tr>
              )}

              {loading && (
                <tr>
                  <td colSpan={5} className="px-4 py-8 text-center text-sm text-slate-400">
                    Yükleniyor...
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}
