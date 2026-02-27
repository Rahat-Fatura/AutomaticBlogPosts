'use client'

import React, { createContext, useCallback, useContext, useMemo, useState } from 'react'

type ToastItem = {
  id: string
  variant: 'success' | 'error' | 'info'
  message: string
}

type ToastContextValue = {
  push: (t: Omit<ToastItem, 'id'>) => void
}

const ToastContext = createContext<ToastContextValue | null>(null)

export function ToastProvider({ children }: { children: React.ReactNode }) {
  const [items, setItems] = useState<ToastItem[]>([])

  const push = useCallback((t: Omit<ToastItem, 'id'>) => {
    const id = `${Date.now()}-${Math.random()}`
    const item: ToastItem = { id, ...t }
    setItems((prev) => [...prev, item])
    window.setTimeout(() => {
      setItems((prev) => prev.filter((x) => x.id !== id))
    }, 3000)
  }, [])

  const value = useMemo(() => ({ push }), [push])

  return (
    <ToastContext.Provider value={value}>
      {children}
      <div className="fixed right-4 top-4 z-[60] flex w-[340px] max-w-[calc(100vw-2rem)] flex-col gap-2">
        {items.map((t) => {
          const cls =
            t.variant === 'success'
              ? 'border-green-500/30 bg-green-500/10 text-green-200'
              : t.variant === 'error'
                ? 'border-red-500/30 bg-red-500/10 text-red-200'
                : 'border-slate-500/30 bg-slate-500/10 text-slate-200'

          return (
            <div key={t.id} className={`rounded-xl border px-4 py-3 text-sm ${cls}`}>
              {t.message}
            </div>
          )
        })}
      </div>
    </ToastContext.Provider>
  )
}

export function useToast() {
  const ctx = useContext(ToastContext)
  if (!ctx) throw new Error('useToast must be used within ToastProvider')
  return ctx
}
