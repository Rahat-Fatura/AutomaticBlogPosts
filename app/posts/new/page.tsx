'use client'

import { Suspense } from 'react'
import { useSearchParams } from 'next/navigation'
import PostEditor from '@/components/posts/PostEditor'

function NewPostPageContent() {
  const params = useSearchParams()

  const title = params.get('title') || ''
  const content = params.get('content') || ''
  const excerpt = params.get('excerpt') || ''
  const metaDescription = params.get('metaDescription') || ''
  const originalUrl = params.get('originalUrl') || ''

  return (
    <PostEditor
      mode="new"
      initialTitle={title}
      initialContent={content}
      initialExcerpt={excerpt}
      initialMetaDescription={metaDescription}
      originalUrl={originalUrl}
    />
  )
}

export default function NewPostPage() {
  return (
    <Suspense fallback={<div className="text-slate-400">Yükleniyor...</div>}>
      <NewPostPageContent />
    </Suspense>
  )
}
