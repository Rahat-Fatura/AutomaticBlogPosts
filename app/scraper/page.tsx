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

const PERIOD_KEY = 'scraper_period'

type PeriodValue = 'manual' | '1h' | '6h' | '24h'

function periodMs(v: PeriodValue) {
  if (v === '1h') return 60 * 60 * 1000
  if (v === '6h') return 6 * 60 * 60 * 1000
  if (v === '24h') return 24 * 60 * 60 * 1000
  return null
}

export default function ScraperPage() {
  const toast = useToast()
  const router = useRouter()

  const [running, setRunning] = useState(false)
  const [lastRun, setLastRun] = useState<string | null>(null)
  const [results, setResults] = useState<ScraperResult>({ meta: { scraped_at: null, total_count: 0 }, sources: [] })
  const [drafted, setDrafted] = useState<Set<string>>(new Set())

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

    const draftedUrls = Array.isArray(json?.draftedOriginalUrls) ? json.draftedOriginalUrls : []
    setDrafted(new Set(draftedUrls.filter((u) => typeof u === 'string' && u.trim().length > 0)))
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

  async function syncToDb() {
    const res = await fetch('/api/scraper/sync', { method: 'POST' })
    const json = (await res.json()) as { synced?: number; skipped?: number; status?: string; message?: string }
    if (!res.ok || json.status === 'error') {
      throw new Error(json.message || 'DB sync başarısız')
    }
    toast.push({ variant: 'success', message: `DB sync: ${json.synced ?? 0} eklendi, ${json.skipped ?? 0} atlandı.` })
  }

  async function convertToDraft(r: Row) {
    try {
      await syncToDb()
      const qs = new URLSearchParams({
        title: r.baslik,
        content: r.ozet,
        originalUrl: r.link,
      })
      setDrafted((prev) => {
        const next = new Set(prev)
        next.add(r.link)
        return next
      })
      router.push(`/drafts/new?${qs.toString()}`)
    } catch (e) {
      const message = e instanceof Error ? e.message : 'Taslağa dönüştürme başarısız.'
      toast.push({ variant: 'error', message })
    }
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
          <div className="text-lg font-semibold text-slate-100">Scraper</div>
          <div className="mt-1 text-xs text-slate-400">
            Son çalışma: {lastRun ? new Date(lastRun).toLocaleString() : '-'}
          </div>
        </div>

        <div className="flex flex-wrap items-center gap-2">
          <select
            value={period}
            onChange={(e) => setPeriod(e.target.value as PeriodValue)}
            className="rounded-lg border border-slate-700/50 bg-slate-900/40 px-3 py-2 text-sm text-slate-100 outline-none"
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
            className="rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-slate-100 hover:bg-slate-600 disabled:opacity-60"
          >
            {running ? 'Çalışıyor...' : 'Şimdi Çalıştır'}
          </button>
        </div>
      </div>

      <div className="rounded-xl border border-slate-700/50 bg-slate-800">
        <div className="border-b border-slate-700/50 px-4 py-3">
          <div className="text-sm font-medium text-slate-100">
            Duyurular ({rows.length})
          </div>
          <div className="mt-1 text-xs text-slate-400">
            Not: "Taslağa Dönüştür" tıklayınca önce JSON DB'ye sync edilir.
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="min-w-full text-left text-sm">
            <thead className="bg-slate-900/40 text-xs text-slate-300">
              <tr>
                <th className="px-4 py-3">Başlık</th>
                <th className="px-4 py-3">Kaynak</th>
                <th className="px-4 py-3">Tarih</th>
                <th className="px-4 py-3">İşlem</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-700/50">
              {rows.map((r) => (
                <tr key={r.link} className="text-slate-200">
                  <td className="px-4 py-3">
                    <div className="flex flex-wrap items-center gap-2">
                      <div className="font-medium text-slate-100">{r.baslik}</div>
                      {drafted.has(r.link) && (
                        <span className="rounded-full bg-amber-900/40 px-2 py-0.5 text-[11px] font-medium text-amber-200">
                          Taslak
                        </span>
                      )}
                    </div>
                    <a href={r.link} target="_blank" className="mt-1 block text-xs text-sky-300 hover:text-sky-200">
                      {r.link}
                    </a>
                  </td>
                  <td className="px-4 py-3 text-slate-300">{r.kaynak}</td>
                  <td className="px-4 py-3 text-slate-300">{r.tarih || '-'}</td>
                  <td className="px-4 py-3">
                    <button
                      type="button"
                      onClick={() => void convertToDraft(r)}
                      className="rounded-lg bg-slate-700 px-3 py-2 text-xs font-medium text-slate-100 hover:bg-slate-600"
                    >
                      {drafted.has(r.link) ? 'Taslağa Dönüştürüldü' : 'Taslağa Dönüştür'}
                    </button>
                  </td>
                </tr>
              ))}

              {rows.length === 0 && (
                <tr>
                  <td colSpan={4} className="px-4 py-8 text-center text-sm text-slate-400">
                    Kayıt yok. "Şimdi Çalıştır" ile veri çekebilirsin.
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
