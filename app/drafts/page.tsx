'use client'

import { useEffect, useMemo, useState } from 'react'
import Link from 'next/link'
import { useToast } from '@/components/ui/Toast'

type DraftRow = {
  id: string
  title: string
  status: 'pending' | 'approved' | 'published' | 'rejected'
  createdAt: string
  publishedAt: string | null
  announcement: {
    originalUrl: string
    fetchedAt: string
    source: {
      name: string
      url: string
    }
  }
}

export default function DraftsPage() {
  const toast = useToast()
  const [loading, setLoading] = useState(true)
  const [drafts, setDrafts] = useState<DraftRow[]>([])

  async function load() {
    setLoading(true)
    try {
      const res = await fetch('/api/drafts', { cache: 'no-store' })
      if (!res.ok) throw new Error('load failed')
      const json = (await res.json()) as DraftRow[]
      setDrafts(json)
    } catch {
      toast.push({ variant: 'error', message: 'Taslaklar yüklenemedi.' })
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    void load()
  }, [])

  const counts = useMemo(() => {
    const c = { pending: 0, approved: 0, published: 0, rejected: 0 }
    for (const d of drafts) c[d.status] += 1
    return c
  }, [drafts])

  return (
    <div className="mx-auto w-full max-w-6xl">
      <div className="mb-4 flex items-start justify-between gap-3">
        <div>
          <div className="text-lg font-semibold text-slate-100">Taslaklar</div>
          <div className="mt-1 text-xs text-slate-400">
            pending: {counts.pending} · approved: {counts.approved} · published: {counts.published} · rejected: {counts.rejected}
          </div>
        </div>
        <Link
          href="/scraper"
          className="rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-slate-100 hover:bg-slate-600"
        >
          Scraper'a Git
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
                <th className="px-4 py-3">Kaynak</th>
                <th className="px-4 py-3">Durum</th>
                <th className="px-4 py-3">Tarih</th>
                <th className="px-4 py-3">İşlem</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-700/50">
              {drafts.map((d) => (
                <tr key={d.id} className="text-slate-200">
                  <td className="px-4 py-3">
                    <div className="font-medium text-slate-100">{d.title}</div>
                    <a
                      href={d.announcement.originalUrl}
                      target="_blank"
                      className="mt-1 block text-xs text-sky-300 hover:text-sky-200"
                    >
                      {d.announcement.originalUrl}
                    </a>
                  </td>
                  <td className="px-4 py-3 text-slate-300">{d.announcement.source.name}</td>
                  <td className="px-4 py-3 text-slate-300">{d.status}</td>
                  <td className="px-4 py-3 text-slate-300">
                    {new Date(d.createdAt).toLocaleString()}
                  </td>
                  <td className="px-4 py-3">
                    <Link
                      href={`/drafts/new?draftId=${encodeURIComponent(d.id)}`}
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
                    Taslak yok.
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
