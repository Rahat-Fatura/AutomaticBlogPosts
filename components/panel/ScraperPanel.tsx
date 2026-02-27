'use client'

import { useEffect, useMemo, useState } from 'react'
import { useRouter } from 'next/navigation'
import { useToast } from '@/components/ui/Toast'

type ScraperResult = {
  meta?: {
    scraped_at?: string | null
    total_count?: number
  }
  draftedOriginalUrls?: string[]
  sources?: Array<{
    source_name?: string
    source_url?: string
    scraped_at?: string
    count?: number
    error?: string
    duyurular?: Array<{
      baslik?: string
      ozet?: string
      link?: string
      tarih?: string
    }>
  }>
}

type Row = {
  baslik: string
  kaynak: string
  tarih: string
  link: string
  ozet: string
}

type AiResult = {
  originalUrl?: string
  aiStatus?: 'none' | 'queued' | 'running' | 'done' | 'error'
  aiTitle?: string | null
  aiExcerpt?: string | null
  aiMetaDescription?: string | null
  aiContent?: string | null
  aiUpdatedAt?: string | null
  error?: string
  retryAfterSeconds?: number
}

const PERIOD_KEY = 'scraper_period'

type PeriodValue = 'manual' | '1h' | '6h' | '24h'

function periodMs(v: PeriodValue) {
  if (v === '1h') return 60 * 60 * 1000
  if (v === '6h') return 6 * 60 * 60 * 1000
  if (v === '24h') return 24 * 60 * 60 * 1000
  return null
}

export default function ScraperPanel() {
  const toast = useToast()
  const router = useRouter()

  const [running, setRunning] = useState(false)
  const [lastRun, setLastRun] = useState<string | null>(null)
  const [results, setResults] = useState<ScraperResult>({ meta: { scraped_at: null, total_count: 0 }, sources: [] })
  const [aiByLink, setAiByLink] = useState<Record<string, AiResult>>({})
  const [aiRunning, setAiRunning] = useState<Set<string>>(new Set())

  const [period, setPeriod] = useState<PeriodValue>('manual')

  const rows = useMemo<Row[]>(() => {
    const out: Row[] = []
    const sources = Array.isArray(results.sources) ? results.sources : []
    for (const s of sources) {
      const sourceName = (s.source_name || '').trim()
      const duyurular = Array.isArray(s.duyurular) ? s.duyurular : []
      for (const d of duyurular) {
        const baslik = (d.baslik || '').trim()
        const link = (d.link || '').trim()
        if (!baslik || !link) continue
        out.push({
          baslik,
          kaynak: sourceName || '-',
          tarih: (d.tarih || '').trim(),
          link,
          ozet: (d.ozet || '').trim(),
        })
      }
    }
    return out
  }, [results])

  async function loadResults() {
    const res = await fetch('/api/scraper/results?includeDrafts=1', { cache: 'no-store' })
    const json = (await res.json()) as ScraperResult
    setResults(json)
    setLastRun(json?.meta?.scraped_at || null)

    // Load AI status for all announcements
    const allLinks: string[] = []
    const sources = Array.isArray(json.sources) ? json.sources : []
    for (const s of sources) {
      const duyurular = Array.isArray(s.duyurular) ? s.duyurular : []
      for (const d of duyurular) {
        const link = (d.link || '').trim()
        if (link) allLinks.push(link)
      }
    }

    if (allLinks.length > 0) {
      try {
        const aiRes = await fetch('/api/announcements/ai/status', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ originalUrls: allLinks }),
        })
        if (aiRes.ok) {
          const aiData = (await aiRes.json()) as Record<string, AiResult>
          setAiByLink(aiData)
        }
      } catch {
        // ignore
      }
    }
  }

  async function run() {
    setRunning(true)
    try {
      const res = await fetch('/api/scraper/run', { method: 'POST' })
      const json = (await res.json()) as { status?: string; count?: number; message?: string }
      if (!res.ok || json.status === 'error') {
        throw new Error(json.message || 'Scraper çalıştırılamadı')
      }
      toast.push({ variant: 'success', message: `Tamamlandı. ${json.count ?? 0} kayıt.` })
      await loadResults()
    } catch (e) {
      const message = e instanceof Error ? e.message : 'Scraper hata verdi.'
      toast.push({ variant: 'error', message })
    } finally {
      setRunning(false)
    }
  }

  async function generateAi(r: Row) {
    const link = r.link
    if (!link) return

    setAiRunning((prev) => {
      const next = new Set(prev)
      next.add(link)
      return next
    })

    try {
      const res = await fetch('/api/announcements/ai/generate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ originalUrl: link }),
      })

      const json = (await res.json().catch(() => null)) as AiResult | null
      if (!res.ok) throw new Error(json?.error || 'AI üretimi başarısız.')

      setAiByLink((prev) => ({ ...prev, [link]: json || {} }))
      toast.push({ variant: 'success', message: 'AI içerik oluşturuldu.' })
    } catch (e) {
      const message = e instanceof Error ? e.message : 'AI üretimi başarısız.'
      toast.push({ variant: 'error', message })
    } finally {
      setAiRunning((prev) => {
        const next = new Set(prev)
        next.delete(link)
        return next
      })
    }
  }

  function openAiInEditor(r: Row) {
    const link = r.link
    const ai = aiByLink[link]
    const title = (ai?.aiTitle || r.baslik || '').trim()
    const content = (ai?.aiContent || '').trim()
    const excerpt = (ai?.aiExcerpt || '').trim()
    const metaDescription = (ai?.aiMetaDescription || '').trim()

    if (!content) {
      toast.push({ variant: 'info', message: 'Önce AI üretmelisin.' })
      return
    }

    const qs = new URLSearchParams({
      title,
      content,
      excerpt,
      metaDescription,
      originalUrl: link,
    })
    router.push(`/posts/new?${qs.toString()}`)
  }


  useEffect(() => {
    const saved = (typeof window !== 'undefined' ? window.localStorage.getItem(PERIOD_KEY) : null) as PeriodValue | null
    if (saved === 'manual' || saved === '1h' || saved === '6h' || saved === '24h') {
      setPeriod(saved)
    }
  }, [])

  useEffect(() => {
    void loadResults()
  }, [])

  useEffect(() => {
    if (typeof window === 'undefined') return
    window.localStorage.setItem(PERIOD_KEY, period)

    const ms = periodMs(period)
    if (!ms) return

    const id = window.setInterval(() => {
      if (!running) void run()
    }, ms)

    return () => window.clearInterval(id)
  }, [period, running])

  return (
    <div className="mx-auto w-full max-w-6xl">
      <div className="mb-4 flex items-start justify-between gap-3">
        <div>
          <div className="text-lg font-semibold text-gray-900">Scraper</div>
          <div className="mt-1 text-xs text-gray-600">Son çalışma: {lastRun ? new Date(lastRun).toLocaleString() : '-'}</div>
        </div>

        <div className="flex flex-wrap items-center gap-2">
          <select
            value={period}
            onChange={(e) => setPeriod(e.target.value as PeriodValue)}
            className="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 outline-none"
          >
            <option value="manual">Manuel</option>
            <option value="1h">1 saat</option>
            <option value="6h">6 saat</option>
            <option value="24h">24 saat</option>
          </select>

          <button
            type="button"
            disabled={running}
            onClick={() => void run()}
            className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-60"
          >
            {running ? 'Çalışıyor...' : 'Şimdi Çalıştır'}
          </button>
        </div>
      </div>

      <div className="rounded-xl border border-gray-200 bg-white shadow-lg">
        <div className="border-b border-gray-200 px-5 py-4">
          <div className="text-sm font-semibold text-gray-900">Duyurular ({rows.length})</div>
          <div className="mt-1 text-xs text-gray-600">AI ile blog yazısı oluştur ve WordPress'e yayınla.</div>
        </div>

        <div className="overflow-x-auto">
          <table className="min-w-full text-left text-sm">
            <thead className="bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-700">
              <tr>
                <th className="px-5 py-3.5">Başlık</th>
                <th className="px-5 py-3.5">Kaynak</th>
                <th className="px-5 py-3.5">Tarih</th>
                <th className="px-5 py-3.5">İşlem</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {rows.map((r) => (
                <tr key={r.link} className="text-gray-900 transition-colors hover:bg-gray-50">
                  <td className="px-5 py-4">
                    <div className="flex flex-wrap items-center gap-2.5">
                      <div className="font-semibold text-gray-900">{r.baslik}</div>
                      {aiByLink[r.link]?.aiContent && (
                        <span className="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">
                          <svg className="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                          </svg>
                          AI Hazır
                        </span>
                      )}
                      {aiRunning.has(r.link) && (
                        <span className="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                          <svg className="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                          </svg>
                          Üretiliyor
                        </span>
                      )}
                    </div>
                    <a href={r.link} target="_blank" className="mt-2 block text-xs text-blue-600 hover:text-blue-700 hover:underline">
                      {r.link}
                    </a>
                    {aiByLink[r.link]?.aiTitle && (
                      <div className="mt-2 rounded-lg bg-green-50 px-3 py-2 text-xs border border-green-200">
                        <span className="font-semibold text-green-700">AI Başlık:</span>
                        <span className="ml-2 text-gray-700">{aiByLink[r.link].aiTitle}</span>
                      </div>
                    )}
                  </td>
                  <td className="px-5 py-4">
                    <span className="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                      {r.kaynak}
                    </span>
                  </td>
                  <td className="px-5 py-4 text-sm text-gray-600">{r.tarih || '-'}</td>
                  <td className="px-5 py-4">
                    <div className="flex flex-wrap gap-2">
                      <button
                        type="button"
                        disabled={aiRunning.has(r.link)}
                        onClick={() => void generateAi(r)}
                        className="rounded-lg bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        {aiRunning.has(r.link) ? 'AI...' : 'AI Üret'}
                      </button>
                      <button
                        type="button"
                        disabled={!aiByLink[r.link]?.aiContent}
                        onClick={() => openAiInEditor(r)}
                        className="rounded-lg bg-gray-200 px-3.5 py-2 text-xs font-semibold text-gray-900 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        Editöre Git
                      </button>
                    </div>
                  </td>
                </tr>
              ))}

              {rows.length === 0 && (
                <tr>
                  <td colSpan={4} className="px-5 py-12 text-center">
                    <div className="text-sm font-medium text-gray-600">Henüz kayıt yok</div>
                    <div className="mt-1 text-xs text-gray-500">"Şimdi Çalıştır" butonuna tıklayarak veri çekebilirsin</div>
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
