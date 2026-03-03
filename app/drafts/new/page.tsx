'use client'

import { Suspense, useEffect, useMemo, useState } from 'react'
import { useRouter, useSearchParams } from 'next/navigation'
import { EditorContent, useEditor } from '@tiptap/react'
import StarterKit from '@tiptap/starter-kit'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'
import { useToast } from '@/components/ui/Toast'

function NewDraftPageContent() {
  const toast = useToast()
  const router = useRouter()
  const params = useSearchParams()

  const draftId = params.get('draftId') || ''

  const initialTitle = params.get('title') || ''
  const initialContent = params.get('content') || ''
  const initialOriginalUrl = params.get('originalUrl') || ''

  const [title, setTitle] = useState(initialTitle)
  const [metaDescription, setMetaDescription] = useState('')
  const [originalUrl, setOriginalUrl] = useState(initialOriginalUrl)
  const [saving, setSaving] = useState(false)
  const [loading, setLoading] = useState(false)
  const [currentDraftId, setCurrentDraftId] = useState(draftId)

  function goPanelDrafts() {
    try {
      window.localStorage.setItem('panel_tab', 'drafts')
    } catch {
      // ignore
    }
    router.push('/panel')
  }

  const editor = useEditor({
    immediatelyRender: false,
    extensions: [StarterKit, Link.configure({ openOnClick: false }), Image.configure({ inline: false })],
    content: initialContent || '<p></p>',
    editorProps: {
      attributes: {
        class:
          'tiptap-content max-w-none min-h-56 rounded-lg border border-slate-700/50 bg-slate-900/40 px-4 py-3 text-slate-100 outline-none focus:border-slate-500 focus:ring-2 focus:ring-sky-500/20',
      },
    },
  })

  useEffect(() => {
    setTitle(initialTitle)
  }, [initialTitle])

  useEffect(() => {
    setOriginalUrl(initialOriginalUrl)
  }, [initialOriginalUrl])

  useEffect(() => {
    if (!draftId) return
    if (!editor) return

    const ed = editor

    let cancelled = false
    async function loadDraft() {
      setLoading(true)
      try {
        const res = await fetch(`/api/drafts/${encodeURIComponent(draftId)}`, { cache: 'no-store' })
        const json = (await res.json().catch(() => null)) as
          | {
              id?: string
              title?: string
              content?: string
              metaDescription?: string | null
              originalUrl?: string
              error?: string
            }
          | null

        if (!res.ok) throw new Error(json?.error || 'Taslak yüklenemedi')
        if (cancelled) return

        setCurrentDraftId(draftId)
        setTitle(json?.title ?? '')
        setMetaDescription(json?.metaDescription ?? '')
        setOriginalUrl(json?.originalUrl ?? '')
        ed.commands.setContent(json?.content ?? '<p></p>')
      } catch (e) {
        const message = e instanceof Error ? e.message : 'Taslak yüklenemedi.'
        toast.push({ variant: 'error', message })
      } finally {
        if (!cancelled) setLoading(false)
      }
    }

    void loadDraft()
    return () => {
      cancelled = true
    }
  }, [draftId, editor, toast])

  const canSave = useMemo(() => {
    return !!title.trim() && !!(editor?.getText() || '').trim() && !saving && !loading
  }, [title, editor, saving, loading])

  async function saveToDb(status: 'pending' | 'published') {
    setSaving(true)
    try {
      const html = editor?.getHTML() ?? ''
      const res = await fetch('/api/drafts', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          title,
          content: html,
          metaDescription: metaDescription || null,
          status,
          originalUrl,
        }),
      })

      const json = (await res.json().catch(() => null)) as { id?: string; error?: string } | null
      if (!res.ok) {
        throw new Error(json?.error || 'Taslak kaydedilemedi')
      }

      if (json?.id) setCurrentDraftId(json.id)

      toast.push({ variant: 'success', message: status === 'published' ? 'Yayınlandı.' : 'Taslak kaydedildi.' })
    } catch (e) {
      const message = e instanceof Error ? e.message : 'Kaydetme başarısız.'
      toast.push({ variant: 'error', message })
    } finally {
      setSaving(false)
    }
  }

  async function publishToWordpress() {
    try {
      if (!currentDraftId) {
        await saveToDb('pending')
      }

      const id = currentDraftId
      if (!id) {
        throw new Error('Taslak ID alınamadı')
      }

      const res = await fetch('/api/drafts/publish', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ draftId: id }),
      })

      const json = (await res.json().catch(() => null)) as { ok?: boolean; error?: string; postId?: number } | null
      if (!res.ok || !json?.ok) {
        throw new Error(json?.error || 'WordPress yayınlama başarısız')
      }

      toast.push({ variant: 'success', message: `WordPress'e yayınlandı. (ID: ${json.postId ?? '-'})` })
      router.push('/panel')
    } catch (e) {
      const message = e instanceof Error ? e.message : 'Yayınlama başarısız.'
      toast.push({ variant: 'error', message })
    }
  }

  return (
    <div className="mx-auto w-full max-w-6xl">
      <div className="mb-4 flex flex-col gap-1">
        <div className="text-lg font-semibold text-slate-100">{draftId ? 'Taslağı Düzenle' : 'Yeni Taslak'}</div>
        <div className="text-xs text-slate-400 break-all">
          Kaynak URL: {originalUrl || '-'}
        </div>
      </div>

      <div className="grid grid-cols-1 gap-4 pb-8">
        <div className="rounded-xl border border-slate-700/50 bg-slate-800">
          <div className="border-b border-slate-700/50 p-4">
            <div className="mb-1 text-xs font-medium text-slate-300">Başlık</div>
            <input
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              className="w-full rounded-lg border border-slate-700/50 bg-slate-900/40 px-3 py-2 text-base font-medium text-slate-100 outline-none focus:border-slate-500"
            />
          </div>

          <div className="p-4">
            {editor ? <EditorContent editor={editor} /> : <div className="text-sm text-slate-400">Editör hazırlanıyor...</div>}
          </div>
        </div>

        <div className="rounded-xl border border-slate-700/50 bg-slate-800 p-4">
          <div className="mb-2 text-sm font-medium text-slate-100">SEO Açıklaması</div>
          <textarea
            value={metaDescription}
            onChange={(e) => setMetaDescription(e.target.value.slice(0, 155))}
            className="min-h-24 w-full rounded-lg border border-slate-700/50 bg-slate-900/40 px-3 py-2 text-sm text-slate-100 outline-none focus:border-slate-500"
          />
          <div className="mt-1 text-right text-xs text-slate-400">{metaDescription.length}/155</div>
        </div>

        <div className="rounded-xl border border-slate-700/50 bg-slate-800 p-4">
          <div className="flex flex-wrap items-center justify-between gap-3">
            <div className="text-xs text-slate-400">DB: Draft</div>
            <div className="flex flex-wrap gap-2">
              <button
                type="button"
                disabled={!canSave}
                onClick={() => void saveToDb('pending')}
                className="rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-slate-100 hover:bg-slate-600 disabled:opacity-60"
              >
                💾 Taslak Kaydet
              </button>
              <button
                type="button"
                disabled={!canSave}
                onClick={() => void publishToWordpress()}
                className="rounded-lg bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-500 disabled:opacity-60"
              >
                Yayınla
              </button>
              <button
                type="button"
                onClick={() => goPanelDrafts()}
                className="rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-slate-100 hover:bg-slate-600"
              >
                Listeye Dön
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default function NewDraftPage() {
  return (
    <Suspense fallback={<div className="text-slate-400">Yükleniyor...</div>}>
      <NewDraftPageContent />
    </Suspense>
  )
}
