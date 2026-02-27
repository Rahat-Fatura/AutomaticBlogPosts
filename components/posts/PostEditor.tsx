'use client'

import { useEffect, useMemo, useState } from 'react'
import { useRouter } from 'next/navigation'
import { EditorContent, useEditor } from '@tiptap/react'
import StarterKit from '@tiptap/starter-kit'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'
import type { WPPost } from '@/lib/types'
import ImageUploader from '@/components/posts/ImageUploader'
import PublishButton from '@/components/posts/PublishButton'
import { useToast } from '@/components/ui/Toast'

function slugify(input: string) {
  return input
    .trim()
    .toLowerCase()
    .replaceAll('ğ', 'g')
    .replaceAll('ü', 'u')
    .replaceAll('ş', 's')
    .replaceAll('ı', 'i')
    .replaceAll('ö', 'o')
    .replaceAll('ç', 'c')
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
}

export default function PostEditor({
  mode,
  id,
  initialTitle,
  initialContent,
  initialExcerpt,
  initialMetaDescription,
  originalUrl,
}: {
  mode: 'new' | 'edit'
  id?: number
  initialTitle?: string
  initialContent?: string
  initialExcerpt?: string
  initialMetaDescription?: string
  originalUrl?: string
}) {
  const router = useRouter()
  const toast = useToast()

  function goPanelPosts() {
    try {
      window.localStorage.setItem('panel_tab', 'posts')
    } catch {
      // ignore
    }
    router.push('/panel')
  }

  const [loading, setLoading] = useState(mode === 'edit')
  const [saving, setSaving] = useState(false)
  const [postId, setPostId] = useState<number | null>(mode === 'edit' ? id ?? null : null)

  const [title, setTitle] = useState(mode === 'new' ? (initialTitle ?? '') : '')
  const [slug, setSlug] = useState('')
  const [excerpt, setExcerpt] = useState(mode === 'new' ? (initialExcerpt ?? '') : '')
  const [metaDescription, setMetaDescription] = useState(mode === 'new' ? (initialMetaDescription ?? '') : '')
  const [featured, setFeatured] = useState<{ id: number; url: string } | null>(null)
  const [loadedStatus, setLoadedStatus] = useState<'publish' | 'draft'>('draft')
  const [aiPrompt, setAiPrompt] = useState('')
  const [aiLoading, setAiLoading] = useState(false)
  const [rawContent, setRawContent] = useState<string | null>(null)
  const [showRawContent, setShowRawContent] = useState(false)

  const editor = useEditor({
    immediatelyRender: false,
    extensions: [
      StarterKit,
      Link.configure({ openOnClick: false }),
      Image.configure({ inline: false }),
    ],
    content: mode === 'new' ? (initialContent ?? '<p>İçerik buraya...</p>') : '<p>İçerik buraya...</p>',
    editorProps: {
      attributes: {
        class:
          'tiptap-content max-w-none min-h-56 rounded-lg border border-slate-700/50 bg-slate-900/40 px-4 py-3 text-slate-100 outline-none focus:border-slate-500 focus:ring-2 focus:ring-sky-500/20',
      },
    },
  })

  useEffect(() => {
    if (mode !== 'new') return
    if (typeof initialTitle === 'string') setTitle(initialTitle)
    if (typeof initialExcerpt === 'string') setExcerpt(initialExcerpt)
    if (typeof initialMetaDescription === 'string') setMetaDescription(initialMetaDescription)
    if (typeof initialContent === 'string' && editor) {
      editor.commands.setContent(initialContent)
    }
    if (originalUrl) {
      void fetchRawContent(originalUrl)
    }
  }, [mode, initialTitle, initialContent, initialExcerpt, initialMetaDescription, editor, originalUrl])

  async function fetchRawContent(url: string) {
    try {
      const res = await fetch(`/api/announcements/raw?originalUrl=${encodeURIComponent(url)}`)
      if (!res.ok) return
      const json = (await res.json()) as { content?: string }
      if (json.content) {
        setRawContent(json.content)
        setShowRawContent(true)
      }
    } catch {
      // ignore
    }
  }

  async function fetchRawContentByWpPostId(wpPostId: number) {
    try {
      const res = await fetch(`/api/announcements/by-wp-post?wpPostId=${wpPostId}`)
      if (!res.ok) return
      const json = (await res.json()) as { content?: string; originalUrl?: string }
      if (json.content) {
        setRawContent(json.content)
        setShowRawContent(true)
      }
    } catch {
      // ignore
    }
  }

  const canPublish = useMemo(() => {
    return !!postId && !saving
  }, [postId, saving])

  useEffect(() => {
    if (mode !== 'edit') return
    if (!id || Number.isNaN(id)) return

    let cancelled = false
    async function load() {
      setLoading(true)
      try {
        const res = await fetch(`/api/posts/${id}`, { cache: 'no-store' })
        if (!res.ok) throw new Error('not found')
        const p = (await res.json()) as WPPost
        if (cancelled) return

        setPostId(p.id)
        setTitle(p.title?.raw || p.title?.rendered || '')
        setSlug(p.slug || '')
        setExcerpt(p.excerpt?.raw || p.excerpt?.rendered || '')
        setLoadedStatus(p.status === 'publish' ? 'publish' : 'draft')
        editor?.commands.setContent(p.content?.raw || p.content?.rendered || '')
        
        // Load raw content by WordPress post ID
        void fetchRawContentByWpPostId(p.id)
      } catch {
        toast.push({ variant: 'error', message: 'Yazı yüklenemedi.' })
      } finally {
        if (!cancelled) setLoading(false)
      }
    }

    void load()
    return () => {
      cancelled = true
    }
  }, [mode, id, editor, toast])

  useEffect(() => {
    if (!title) return
    if (mode === 'edit' && slug) return
    setSlug(slugify(title))
  }, [title, mode, slug])

  async function generateWithAi() {
    if (mode !== 'new') return
    const prompt = aiPrompt.trim()
    if (!prompt) {
      toast.push({ variant: 'error', message: 'AI prompt boş olamaz.' })
      return
    }

    setAiLoading(true)
    try {
      const res = await fetch('/api/ai/generate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ prompt }),
      })

      const json = (await res.json().catch(() => null)) as
        | {
            text?: string
            error?: string
          }
        | null

      if (!res.ok) throw new Error(json?.error || 'AI üretimi başarısız.')
      const text = (json?.text || '').trim()
      if (!text) throw new Error('AI boş çıktı döndürdü.')

      const html = text
        .split(/\r?\n\r?\n+/)
        .map((p) => p.trim())
        .filter(Boolean)
        .map((p) => `<p>${p.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')}</p>`)
        .join('')

      editor?.commands.setContent(html)
      toast.push({ variant: 'success', message: 'AI içerik oluşturuldu.' })
    } catch (e) {
      const message = e instanceof Error ? e.message : 'AI üretimi başarısız.'
      toast.push({ variant: 'error', message })
    } finally {
      setAiLoading(false)
    }
  }

  async function saveDraft() {
    setSaving(true)
    try {
      const html = editor?.getHTML() ?? ''
      console.log('Saving content HTML:', html)

      const payload: Partial<WPPost> = {
        title: { rendered: title, raw: title },
        slug,
        excerpt: { rendered: excerpt, raw: excerpt },
        content: { rendered: html, raw: html },
        featured_media: featured?.id ?? 0,
        status: postId == null ? 'draft' : loadedStatus,
        meta: originalUrl ? { original_url: originalUrl } : undefined,
      }
      console.log('WordPress payload:', payload)

      if (postId == null) {
        const res = await fetch('/api/posts', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        })
        if (!res.ok) {
          const err = (await res.json().catch(() => null)) as { error?: string } | null
          throw new Error(err?.error || 'create failed')
        }
        const created = (await res.json()) as WPPost
        setPostId(created.id)
        
        // Link WordPress post with Announcement
        if (originalUrl) {
          await fetch('/api/announcements/link-wp-post', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ originalUrl, wpPostId: created.id }),
          }).catch(() => {})
        }
        
        toast.push({ variant: 'success', message: 'Taslak kaydedildi.' })
        router.replace(`/posts/${created.id}`)
        return
      }

      const res = await fetch(`/api/posts/${postId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      })
      if (!res.ok) {
        const err = (await res.json().catch(() => null)) as { error?: string } | null
        throw new Error(err?.error || 'update failed')
      }
      toast.push({ variant: 'success', message: 'Taslak güncellendi.' })
    } catch (e) {
      const message = e instanceof Error ? e.message : 'Kaydetme başarısız.'
      toast.push({ variant: 'error', message })
    } finally {
      setSaving(false)
    }
  }

  async function publish() {
    if (!postId) {
      toast.push({ variant: 'error', message: 'Önce taslak kaydetmelisin.' })
      return
    }

    await saveDraft()

    const res = await fetch(`/api/posts/${postId}/publish`, { method: 'POST' })
    if (!res.ok) {
      const err = (await res.json().catch(() => null)) as { error?: string } | null
      toast.push({ variant: 'error', message: err?.error || 'Publish başarısız.' })
      return
    }

    const data = (await res.json()) as { message?: string }
    toast.push({ variant: 'success', message: data.message ?? 'Yayınlandı.' })
    setLoadedStatus('publish')
    goPanelPosts()
  }

  function insertLink() {
    const url = window.prompt('Link URL')
    if (!url) return
    editor?.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
  }

  function unsetLink() {
    editor?.chain().focus().unsetLink().run()
  }

  function insertImage() {
    if (!featured?.url) {
      toast.push({ variant: 'info', message: 'Önce öne çıkan görsel seç.' })
      return
    }
    console.log('Inserting image:', featured.url)
    editor?.chain().focus().setImage({ src: featured.url }).run()
    toast.push({ variant: 'success', message: 'Görsel eklendi.' })
  }

  if (loading) {
    return <div className="text-sm text-slate-300">Yükleniyor...</div>
  }

  const toolbarBtn = (active?: boolean) =>
    `rounded-lg px-2 py-1 text-xs text-slate-100 hover:bg-slate-600 ${
      active ? 'bg-slate-600' : 'bg-slate-700'
    }`

  return (
    <div className="mx-auto w-full max-w-6xl">
      <div className="mb-4 flex flex-col gap-1">
        <div className="text-lg font-semibold text-slate-100">
          {mode === 'new' ? 'Yeni Yazı' : 'Yazıyı Düzenle'}
        </div>
        <div className="text-xs text-slate-400">
          {postId ? `ID: ${postId}` : 'Henüz kaydedilmedi'}
        </div>
      </div>

      {rawContent && (
        <div className="mb-4 flex items-center justify-between rounded-lg border border-sky-700/50 bg-sky-900/20 px-4 py-2">
          <div className="text-sm text-sky-200">
            <span className="font-medium">Ham İçerik Mevcut</span> — Orijinal duyuru metnini görmek için yan paneli aç
          </div>
          <button
            type="button"
            onClick={() => setShowRawContent(!showRawContent)}
            className="rounded-lg bg-sky-700 px-3 py-1.5 text-xs font-medium text-slate-100 hover:bg-sky-600"
          >
            {showRawContent ? 'Gizle' : 'Göster'}
          </button>
        </div>
      )}

      <div className={`grid gap-4 pb-8 ${showRawContent ? 'grid-cols-1 lg:grid-cols-2' : 'grid-cols-1'}`}>
        {showRawContent && rawContent && (
          <div className="rounded-xl border border-slate-700/50 bg-slate-800">
            <div className="border-b border-slate-700/50 px-4 py-3">
              <div className="text-sm font-medium text-slate-100">Orijinal Ham İçerik</div>
              <div className="mt-1 text-xs text-slate-400">Scraper tarafından çekilen kaynak metin</div>
            </div>
            <div className="max-h-[600px] overflow-y-auto p-4">
              <div className="whitespace-pre-wrap text-sm text-slate-300">{rawContent}</div>
            </div>
          </div>
        )}

        <div className="rounded-xl border border-slate-700/50 bg-slate-800">
          <div className="border-b border-slate-700/50 p-4">
            <div className="grid grid-cols-1 gap-3">
              <div>
                <div className="mb-1 text-xs font-medium text-slate-300">Başlık</div>
                <input
                  value={title}
                  onChange={(e) => setTitle(e.target.value)}
                  placeholder="Blog yazısının başlığını girin..."
                  className="w-full rounded-lg border border-slate-700/50 bg-slate-900/40 px-3 py-2 text-base font-medium text-slate-100 outline-none focus:border-slate-500"
                />
              </div>
              {mode === 'new' ? (
                <div>
                  <div className="mb-1 text-xs font-medium text-slate-300">AI Prompt</div>
                  <div className="flex flex-col gap-2 md:flex-row md:items-center">
                    <input
                      value={aiPrompt}
                      onChange={(e) => setAiPrompt(e.target.value)}
                      placeholder='Örn: "X konusu ile ilgili 800 kelimelik SEO uyumlu blog yazısı yaz"'
                      className="w-full flex-1 rounded-lg border border-slate-700/50 bg-slate-900/40 px-3 py-2 text-sm text-slate-100 outline-none focus:border-slate-500"
                    />
                    <button
                      type="button"
                      disabled={!editor || aiLoading}
                      onClick={() => void generateWithAi()}
                      className="rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-slate-100 hover:bg-slate-600 disabled:opacity-60"
                    >
                      {aiLoading ? 'Oluşturuluyor...' : 'AI ile oluştur'}
                    </button>
                  </div>
                </div>
              ) : null}
              <div>
                <div className="mb-1 text-xs font-medium text-slate-300">Slug</div>
                <input
                  value={slug}
                  onChange={(e) => setSlug(slugify(e.target.value))}
                  placeholder="başlıktan-otomatik-uretilir"
                  className="w-full rounded-lg border border-slate-700/50 bg-slate-900/40 px-3 py-2 font-mono text-sm text-slate-100 outline-none focus:border-slate-500"
                />
                <div className="mt-1 text-xs text-slate-400">/{slug || '...'} </div>
              </div>
            </div>
          </div>

          <div className="border-b border-slate-700/50 px-4 py-3">
            <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
              <div className="text-sm font-medium text-slate-100">İçerik</div>
              <div className="flex flex-wrap gap-2">
                <button type="button" onClick={() => editor?.chain().focus().toggleBold().run()} className={toolbarBtn(!!editor?.isActive('bold'))}>
                  B
                </button>
                <button type="button" onClick={() => editor?.chain().focus().toggleItalic().run()} className={toolbarBtn(!!editor?.isActive('italic'))}>
                  I
                </button>
                <button
                  type="button"
                  onClick={() => editor?.chain().focus().toggleHeading({ level: 1 }).run()}
                  className={toolbarBtn(!!editor?.isActive('heading', { level: 1 }))}
                >
                  H1
                </button>
                <button
                  type="button"
                  onClick={() => editor?.chain().focus().toggleHeading({ level: 2 }).run()}
                  className={toolbarBtn(!!editor?.isActive('heading', { level: 2 }))}
                >
                  H2
                </button>
                <button type="button" onClick={insertLink} className={toolbarBtn(!!editor?.isActive('link'))}>
                  Link
                </button>
                <button type="button" onClick={unsetLink} className={toolbarBtn(false)}>
                  Link Kaldır
                </button>
                <button
                  type="button"
                  disabled={!featured?.url}
                  onClick={insertImage}
                  className={`${toolbarBtn(false)} disabled:opacity-50`}
                >
                  Görsel
                </button>
              </div>
            </div>
          </div>

          <div className="p-4">
            {editor ? <EditorContent editor={editor} /> : <div className="text-sm text-slate-400">Editör hazırlanıyor...</div>}
          </div>
        </div>

        <ImageUploader value={featured} onChange={setFeatured} />

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
          <div className="mb-2 text-sm font-medium text-slate-100">Özet</div>
          <textarea
            value={excerpt}
            onChange={(e) => setExcerpt(e.target.value)}
            className="min-h-24 w-full rounded-lg border border-slate-700/50 bg-slate-900/40 px-3 py-2 text-sm text-slate-100 outline-none focus:border-slate-500"
          />
        </div>

        <div className="rounded-xl border border-slate-700/50 bg-slate-800 p-4">
          <div className="flex flex-wrap items-center justify-between gap-3">
            <div className="text-xs text-slate-400">
              {postId ? `ID: ${postId}` : 'Henüz kaydedilmedi'}
            </div>
            <div className="flex flex-wrap gap-2">
              <button
                type="button"
                disabled={saving}
                onClick={() => void saveDraft()}
                className="rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-slate-100 hover:bg-slate-600 disabled:opacity-60"
              >
                💾 Taslak Kaydet
              </button>
              <PublishButton disabled={!canPublish} onPublish={publish} />
              <button
                type="button"
                onClick={() => goPanelPosts()}
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
