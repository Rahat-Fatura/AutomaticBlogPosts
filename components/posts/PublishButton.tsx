'use client'

import { useState } from 'react'
import ConfirmModal from '@/components/ui/ConfirmModal'

export default function PublishButton({
  disabled,
  onPublish,
}: {
  disabled?: boolean
  onPublish: () => Promise<void>
}) {
  const [open, setOpen] = useState(false)

  return (
    <>
      <button
        type="button"
        disabled={disabled}
        onClick={() => setOpen(true)}
        className="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 disabled:opacity-60"
      >
        ✅ Onayla & Publish Et
      </button>
      <ConfirmModal
        open={open}
        title="Yazı yayınlansın mı?"
        description="Onayladığında yazı yayına alınacak (mock modda status publish olur)."
        confirmText="Evet, yayınla"
        cancelText="Vazgeç"
        onCancel={() => setOpen(false)}
        onConfirm={() => {
          setOpen(false)
          void onPublish()
        }}
      />
    </>
  )
}
