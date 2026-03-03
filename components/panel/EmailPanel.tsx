'use client'

import { useEffect, useState } from 'react'

interface EmailRecipient {
  id: string
  name: string
  email: string
  role: string | null
  isActive: boolean
  createdAt: string
  updatedAt: string
}

export default function EmailPanel() {
  const [recipients, setRecipients] = useState<EmailRecipient[]>([])
  const [loading, setLoading] = useState(true)
  const [showAddForm, setShowAddForm] = useState(false)
  const [editingId, setEditingId] = useState<string | null>(null)
  const [formData, setFormData] = useState({ name: '', email: '', role: '' })
  const [testEmailLoading, setTestEmailLoading] = useState(false)
  const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null)

  useEffect(() => {
    fetchRecipients()
  }, [])

  const fetchRecipients = async () => {
    try {
      const res = await fetch('/api/email-recipients')
      if (res.ok) {
        const data = await res.json()
        setRecipients(data)
      }
    } catch (error) {
      console.error('Error fetching recipients:', error)
    } finally {
      setLoading(false)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    
    try {
      const url = editingId 
        ? `/api/email-recipients/${editingId}`
        : '/api/email-recipients'
      
      const method = editingId ? 'PUT' : 'POST'
      
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
      })

      if (res.ok) {
        setMessage({ type: 'success', text: editingId ? 'Alıcı güncellendi' : 'Alıcı eklendi' })
        setFormData({ name: '', email: '', role: '' })
        setShowAddForm(false)
        setEditingId(null)
        fetchRecipients()
        setTimeout(() => setMessage(null), 3000)
      } else {
        const data = await res.json()
        setMessage({ type: 'error', text: data.error || 'Bir hata oluştu' })
      }
    } catch (error) {
      setMessage({ type: 'error', text: 'Bir hata oluştu' })
    }
  }

  const handleEdit = (recipient: EmailRecipient) => {
    setEditingId(recipient.id)
    setFormData({
      name: recipient.name,
      email: recipient.email,
      role: recipient.role || '',
    })
    setShowAddForm(true)
  }

  const handleToggleActive = async (id: string, currentStatus: boolean) => {
    try {
      const res = await fetch(`/api/email-recipients/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ isActive: !currentStatus }),
      })

      if (res.ok) {
        setMessage({ type: 'success', text: 'Durum güncellendi' })
        fetchRecipients()
        setTimeout(() => setMessage(null), 3000)
      }
    } catch (error) {
      setMessage({ type: 'error', text: 'Bir hata oluştu' })
    }
  }

  const handleDelete = async (id: string) => {
    if (!confirm('Bu alıcıyı silmek istediğinizden emin misiniz?')) return

    try {
      const res = await fetch(`/api/email-recipients/${id}`, {
        method: 'DELETE',
      })

      if (res.ok) {
        setMessage({ type: 'success', text: 'Alıcı silindi' })
        fetchRecipients()
        setTimeout(() => setMessage(null), 3000)
      } else {
        const data = await res.json()
        console.error('Delete error:', data)
        setMessage({ type: 'error', text: data.error || 'Bir hata oluştu' })
        setTimeout(() => setMessage(null), 5000)
      }
    } catch (error) {
      console.error('Delete exception:', error)
      setMessage({ type: 'error', text: 'Bir hata oluştu: ' + (error instanceof Error ? error.message : 'Bilinmeyen hata') })
      setTimeout(() => setMessage(null), 5000)
    }
  }

  const handleTestEmail = async () => {
    setTestEmailLoading(true)
    try {
      const res = await fetch('/api/email-recipients/test', {
        method: 'POST',
      })

      const data = await res.json()

      if (res.ok) {
        setMessage({ 
          type: 'success', 
          text: `Test email gönderildi: ${data.recipients.join(', ')}` 
        })
      } else {
        setMessage({ type: 'error', text: data.error || 'Bir hata oluştu' })
      }
    } catch (error) {
      setMessage({ type: 'error', text: 'Bir hata oluştu' })
    } finally {
      setTestEmailLoading(false)
      setTimeout(() => setMessage(null), 5000)
    }
  }

  const cancelEdit = () => {
    setShowAddForm(false)
    setEditingId(null)
    setFormData({ name: '', email: '', role: '' })
  }

  if (loading) {
    return <div className="text-center py-8">Yükleniyor...</div>
  }

  return (
    <div className="space-y-6">
      {message && (
        <div className={`p-4 rounded-lg ${
          message.type === 'success' 
            ? 'bg-green-50 text-green-800 border border-green-200' 
            : 'bg-red-50 text-red-800 border border-red-200'
        }`}>
          {message.text}
        </div>
      )}

      <div className="flex justify-between items-center">
        <div>
          <h2 className="text-xl font-semibold text-gray-900">Email Bildirimleri</h2>
          <p className="text-sm text-gray-600 mt-1">
            Yeni taslaklar oluşturulduğunda bildirim alacak kişileri yönetin
          </p>
        </div>
        <div className="flex gap-2">
          <button
            onClick={handleTestEmail}
            disabled={testEmailLoading || recipients.filter(r => r.isActive).length === 0}
            className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-sm font-medium"
          >
            {testEmailLoading ? 'Gönderiliyor...' : '📧 Test Email Gönder'}
          </button>
          <button
            onClick={() => setShowAddForm(!showAddForm)}
            className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium"
          >
            {showAddForm ? 'İptal' : '+ Yeni Alıcı Ekle'}
          </button>
        </div>
      </div>

      {showAddForm && (
        <div className="bg-white p-6 rounded-lg border border-gray-200">
          <h3 className="text-lg font-semibold mb-4">
            {editingId ? 'Alıcıyı Düzenle' : 'Yeni Alıcı Ekle'}
          </h3>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Ad Soyad *
              </label>
              <input
                type="text"
                required
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Ahmet Yılmaz"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Email *
              </label>
              <input
                type="email"
                required
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="ahmet@example.com"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Rol (Opsiyonel)
              </label>
              <input
                type="text"
                value={formData.role}
                onChange={(e) => setFormData({ ...formData, role: e.target.value })}
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Editör, Yönetici, vb."
              />
            </div>
            <div className="flex gap-2">
              <button
                type="submit"
                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
              >
                {editingId ? 'Güncelle' : 'Ekle'}
              </button>
              <button
                type="button"
                onClick={cancelEdit}
                className="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
              >
                İptal
              </button>
            </div>
          </form>
        </div>
      )}

      <div className="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table className="w-full">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Ad Soyad
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Rol
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Durum
              </th>
              <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                İşlemler
              </th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {recipients.length === 0 ? (
              <tr>
                <td colSpan={5} className="px-6 py-8 text-center text-gray-500">
                  Henüz email alıcısı eklenmemiş
                </td>
              </tr>
            ) : (
              recipients.map((recipient) => (
                <tr key={recipient.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {recipient.name}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {recipient.email}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {recipient.role || '-'}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <button
                      onClick={() => handleToggleActive(recipient.id, recipient.isActive)}
                      className={`px-3 py-1 rounded-full text-xs font-medium ${
                        recipient.isActive
                          ? 'bg-green-100 text-green-800'
                          : 'bg-gray-100 text-gray-800'
                      }`}
                    >
                      {recipient.isActive ? '✓ Aktif' : '○ Pasif'}
                    </button>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button
                      onClick={() => handleEdit(recipient)}
                      className="text-blue-600 hover:text-blue-900 mr-3"
                    >
                      Düzenle
                    </button>
                    <button
                      onClick={() => handleDelete(recipient.id)}
                      className="text-red-600 hover:text-red-900"
                    >
                      Sil
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {recipients.length > 0 && (
        <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
          <p className="text-sm text-blue-800">
            <strong>💡 Bilgi:</strong> Toplam {recipients.length} alıcı, {recipients.filter(r => r.isActive).length} aktif. 
            Yeni taslaklar oluşturulduğunda sadece aktif alıcılara email gönderilir.
          </p>
        </div>
      )}
    </div>
  )
}
