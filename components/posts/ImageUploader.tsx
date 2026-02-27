'use client'

import { useRef, useState } from 'react'
import { useToast } from '@/components/ui/Toast'

export default function ImageUploader({
  value,
  onChange,
}: {
  value?: { id: number; url: string } | null
  onChange: (v: { id: number; url: string } | null) => void
}) {
  const toast = useToast()
  const inputRef = useRef<HTMLInputElement | null>(null)
  const [uploading, setUploading] = useState(false)

  async function upload(file: File) {
    setUploading(true)
    try {
      const fd = new FormData()
      fd.append('file', file)

      const res = await fetch('/api/media', { method: 'POST', body: fd })
      if (!res.ok) {
        toast.push({ variant: 'error', message: 'Görsel yüklenemedi.' })
        return
      }

      const data = (await res.json()) as { id: number; url: string }
      onChange(data)
      toast.push({ variant: 'success', message: 'Görsel seçildi (mock upload).' })
    } finally {
      setUploading(false)
    }
  }

  return (
    <div className="rounded-xl border border-slate-700/50 bg-slate-800 p-4">
      <div className="mb-2 text-sm font-medium text-slate-100">Öne Çıkan Görsel</div>

      <div className="flex flex-col gap-3">
        <div className="flex items-center gap-2">
          <input
            ref={inputRef}
            type="file"
            accept="image/*"
            className="hidden"
            onChange={(e) => {
              const file = e.target.files?.[0]
              if (file) void upload(file)
            }}
          />
          <button
            type="button"
            disabled={uploading}
            onClick={() => inputRef.current?.click()}
            className="rounded-lg bg-slate-700 px-3 py-2 text-sm text-slate-100 hover:bg-slate-600 disabled:opacity-60"
          >
            {uploading ? 'Yükleniyor...' : 'Dosya Seç'}
          </button>
          {value ? (
            <button
              type="button"
              onClick={() => onChange(null)}
              className="rounded-lg bg-slate-700 px-3 py-2 text-sm text-slate-100 hover:bg-slate-600"
            >
              Kaldır
            </button>
          ) : null}
        </div>

        <div className="rounded-lg border border-slate-700/50 bg-slate-900/40 p-3">
          {value ? (
            // eslint-disable-next-line @next/next/no-img-element
            <img src={value.url} alt="preview" className="max-h-56 w-auto rounded-md" />
          ) : (
            <div className="text-sm text-slate-400">Önizleme alanı</div>
          )}
        </div>
      </div>
    </div>
  )
}
