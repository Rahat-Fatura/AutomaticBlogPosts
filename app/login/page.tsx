'use client'

import { Suspense, useEffect, useState } from 'react'
import { useRouter, useSearchParams } from 'next/navigation'
import { useToast } from '@/components/ui/Toast'

function LoginPageContent() {
  const router = useRouter()
  const params = useSearchParams()
  const toast = useToast()

  const [username, setUsername] = useState('')
  const [password, setPassword] = useState('')
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    fetch('/api/auth/me', { cache: 'no-store' })
      .then((r) => (r.ok ? r.json() : null))
      .then((d) => {
        if (d?.ok) {
          const next = params.get('next') || '/posts'
          router.replace(next)
        }
      })
      .catch(() => null)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault()
    setLoading(true)
    try {
      const res = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password }),
      })

      if (!res.ok) {
        const err = (await res.json().catch(() => null)) as { error?: string } | null
        toast.push({ variant: 'error', message: err?.error || 'Giriş başarısız.' })
        return
      }

      const next = params.get('next') || '/posts'
      router.replace(next)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="mx-auto flex min-h-[calc(100dvh-56px)] w-full max-w-md flex-col justify-center px-6 py-10">
      <div className="rounded-2xl border border-slate-700/50 bg-slate-800 p-6 shadow-xl">
        <div className="text-lg font-semibold text-slate-100">Giriş</div>
        <div className="mt-1 text-sm text-slate-400">Admin paneline erişmek için giriş yap.</div>

        <form onSubmit={onSubmit} className="mt-6 flex flex-col gap-3">
          <div>
            <div className="mb-1 text-xs font-medium text-slate-300">Kullanıcı adı</div>
            <input
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              className="w-full rounded-lg border border-slate-700/50 bg-slate-900/40 px-3 py-2 text-sm text-slate-100 outline-none focus:border-slate-500"
              autoComplete="username"
            />
          </div>

          <div>
            <div className="mb-1 text-xs font-medium text-slate-300">Şifre</div>
            <input
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              type="password"
              className="w-full rounded-lg border border-slate-700/50 bg-slate-900/40 px-3 py-2 text-sm text-slate-100 outline-none focus:border-slate-500"
              autoComplete="current-password"
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="mt-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-60"
          >
            {loading ? 'Giriş yapılıyor...' : 'Giriş Yap'}
          </button>

          <div className="mt-1 text-xs text-slate-500">
            Bu giriş bilgileri WordPress kullanıcılarınla aynı olmak zorunda değil.
          </div>
        </form>
      </div>
    </div>
  )
}

export default function LoginPage() {
  return (
    <Suspense fallback={<div className="text-slate-400">Yükleniyor...</div>}>
      <LoginPageContent />
    </Suspense>
  )
}
