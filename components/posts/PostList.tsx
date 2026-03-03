'use client'

import Link from 'next/link'
import { useEffect, useMemo, useState } from 'react'
import { useRouter } from 'next/navigation'
import type { WPPost } from '@/lib/types'
import ConfirmModal from '@/components/ui/ConfirmModal'
import { useToast } from '@/components/ui/Toast'

function decodeHtml(html: string): string {
  if (typeof window === 'undefined') return html
  const txt = document.createElement('textarea')
  txt.innerHTML = html
  return txt.value
}

export default function PostList() {
  const router = useRouter()
  const toast = useToast()

  const [posts, setPosts] = useState<WPPost[]>([])
  const [loading, setLoading] = useState(true)
  const [deleteId, setDeleteId] = useState<number | null>(null)
  const [query, setQuery] = useState('')
  const [status, setStatus] = useState<'all' | 'draft' | 'publish'>('all')

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase()
    return posts
      .filter((p) => {
        if (status === 'all') return true
        return p.status === status
      })
      .filter((p) => {
        if (!q) return true
        const t = (p.title?.rendered || '').toLowerCase()
        const s = (p.slug || '').toLowerCase()
        return t.includes(q) || s.includes(q)
      })
      .sort((a, b) => (a.date < b.date ? 1 : -1))
  }, [posts, query, status])

  async function load() {
    setLoading(true)
    try {
      const res = await fetch('/api/posts', { cache: 'no-store' })
      if (!res.ok) {
        const err = (await res.json().catch(() => null)) as { error?: string } | null
        throw new Error(err?.error || 'load failed')
      }
      const data = (await res.json()) as WPPost[]
      setPosts(data)
    } catch (e) {
      const message = e instanceof Error ? e.message : 'Yazılar yüklenemedi.'
      toast.push({ variant: 'error', message })
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    void load()
  }, [])

  async function onDeleteConfirm() {
    if (deleteId == null) return
    const id = deleteId
    setDeleteId(null)

    const res = await fetch(`/api/posts/${id}`, { method: 'DELETE' })
    if (!res.ok) {
      toast.push({ variant: 'error', message: 'Silme başarısız.' })
      return
    }

    toast.push({ variant: 'success', message: 'Yazı silindi.' })
    await load()
  }

  return (
    <div className="mx-auto w-full max-w-5xl">
      <div className="mb-4 flex items-center justify-between">
        <div className="text-lg font-semibold">Blog Yazıları</div>
        <Link
          href="/posts/new"
          className="rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-500"
        >
          + Yeni Yazı
        </Link>
      </div>

      <div className="border-b border-gray-200 px-4 py-3">
        <div className="flex flex-wrap items-center gap-2">
          <input
            type="text"
            placeholder="Ara..."
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            className="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none placeholder:text-gray-500"
          />
          <button
            type="button"
            onClick={() => setStatus('all')}
            className={`rounded-lg px-3 py-2 text-xs font-medium ${
              status === 'all' ? 'bg-gray-900 text-white' : 'bg-white text-gray-900 hover:bg-gray-100 border border-gray-300'
            }`}
          >
            Tümü
          </button>
          <button
            type="button"
            onClick={() => setStatus('draft')}
            className={`rounded-lg px-3 py-2 text-xs font-medium ${
              status === 'draft' ? 'bg-gray-900 text-white' : 'bg-white text-gray-900 hover:bg-gray-100 border border-gray-300'
            }`}
          >
            Taslak
          </button>
          <button
            type="button"
            onClick={() => setStatus('publish')}
            className={`rounded-lg px-3 py-2 text-xs font-medium ${
              status === 'publish' ? 'bg-gray-900 text-white' : 'bg-white text-gray-900 hover:bg-gray-100 border border-gray-300'
            }`}
          >
            Yayında
          </button>
        </div>
      </div>

      <div className="rounded-xl border border-gray-200 bg-white shadow-lg">
        <div className="border-b border-gray-200 px-4 py-3">
          <div className="text-sm font-medium text-gray-900">Yazılar ({posts.length})</div>
          <div className="mt-1 text-xs text-gray-600">WordPress'te yayınlanan ve taslak yazılar.</div>
        </div>

        <div className="grid grid-cols-12 border-b border-gray-200 px-4 py-3 text-xs font-medium text-gray-900">
          <div className="col-span-6">Başlık</div>
          <div className="col-span-2">Durum</div>
          <div className="col-span-2">Tarih</div>
          <div className="col-span-2 text-right">İşlem</div>
        </div>

        {loading && (
          <div className="px-4 py-8 text-center text-sm text-gray-600">Yükleniyor...</div>
        )}
        {!loading && filtered.length === 0 && (
          <div className="px-4 py-8 text-center text-sm text-gray-600">Yazı bulunamadı.</div>
        )}
        {!loading && filtered.length > 0 && (
          filtered.map((p) => (
            <div
              key={p.id}
              className="grid grid-cols-12 items-center gap-2 border-b border-gray-200 px-4 py-3 text-sm last:border-b-0 hover:bg-gray-50"
            >
              <div className="col-span-6 min-w-0">
                <div className="truncate font-medium text-gray-900">{decodeHtml(p.title?.rendered || '(Başlıksız)')}</div>
                <div className="truncate text-xs text-gray-600">/{p.slug}</div>
              </div>
              <div className="col-span-2">
                <span
                  className={p.status === 'publish' ? 'inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700' : 'inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-700'}
                >
                  {p.status === 'publish' ? 'Yayında' : 'Taslak'}
                </span>
              </div>
              <div className="col-span-2 text-xs text-gray-600">
                {new Date(p.date).toLocaleDateString('tr-TR')}
              </div>
              <div className="col-span-2 flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => router.push(`/posts/${p.id}`)}
                  className="rounded-lg bg-blue-600 px-2 py-1 text-xs text-white hover:bg-blue-700"
                >
                  Düzenle
                </button>
                <button
                  type="button"
                  onClick={() => setDeleteId(p.id)}
                  className="rounded-lg bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700"
                >
                  Sil
                </button>
              </div>
            </div>
          ))
        )}
      </div>

      <ConfirmModal
        open={deleteId != null}
        title="Yazı silinsin mi?"
        description="Bu işlem geri alınamaz (mock modda da listeden kalkar)."
        confirmText="Evet, sil"
        cancelText="Vazgeç"
        onCancel={() => setDeleteId(null)}
        onConfirm={() => void onDeleteConfirm()}
      />
    </div>
  )
}
