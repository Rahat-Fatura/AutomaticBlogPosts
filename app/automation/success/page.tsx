'use client'

import { useSearchParams } from 'next/navigation'
import { Suspense } from 'react'

function SuccessContent() {
  const searchParams = useSearchParams()
  const message = searchParams.get('message') || 'İşlem başarılı'
  const title = searchParams.get('title')

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 p-4">
      <div className="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div className="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg className="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
          </svg>
        </div>
        
        <h1 className="text-2xl font-bold text-gray-900 mb-3">
          ✅ Başarılı!
        </h1>
        
        <p className="text-gray-600 mb-6">
          {message}
        </p>
        
        {title && (
          <div className="bg-gray-50 rounded-lg p-4 mb-6">
            <p className="text-sm text-gray-500 mb-1">Makale:</p>
            <p className="font-medium text-gray-900">{title}</p>
          </div>
        )}
        
        <div className="space-y-3">
          <a
            href="/panel"
            className="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium"
          >
            Admin Panele Git
          </a>
          <button
            onClick={() => window.close()}
            className="block w-full bg-gray-200 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors font-medium"
          >
            Pencereyi Kapat
          </button>
        </div>
        
        <p className="text-xs text-gray-500 mt-6">
          Bu pencereyi güvenle kapatabilirsiniz
        </p>
      </div>
    </div>
  )
}

export default function SuccessPage() {
  return (
    <Suspense fallback={<div className="min-h-screen flex items-center justify-center">Yükleniyor...</div>}>
      <SuccessContent />
    </Suspense>
  )
}
