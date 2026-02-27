export interface WPPost {
  id: number
  title: { rendered: string; raw: string }
  content: { rendered: string; raw: string }
  excerpt: { rendered: string; raw: string }
  status: 'publish' | 'draft' | 'trash' | 'pending'
  slug: string
  date: string
  featured_media: number
  categories: number[]
  tags: number[]
  meta?: {
    original_url?: string
    [key: string]: unknown
  }
}

export interface PostFormData {
  title: string
  content: string
  slug: string
  status: 'publish' | 'draft'
  featured_media?: number
  meta_description?: string
}

export interface ApiResponse<T> {
  data?: T
  error?: string
}
