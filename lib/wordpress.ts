import 'server-only'

import type { WPPost } from './types'

class WPError extends Error {
  status: number
  bodyText: string
  constructor(message: string, status: number, bodyText: string) {
    super(message)
    this.name = 'WPError'
    this.status = status
    this.bodyText = bodyText
  }
}

function tryExtractWpMessage(bodyText: string) {
  try {
    const data = JSON.parse(bodyText) as { message?: string; code?: string }
    const m = data?.message
    const c = data?.code
    if (m && c) return `${c}: ${m}`
    if (m) return m
    return null
  } catch {
    return null
  }
}

function getEnv(name: string) {
  const v = process.env[name]
  if (!v) throw new Error(`Missing env: ${name}`)
  return v
}

function getBaseUrl() {
  const raw = getEnv('WORDPRESS_URL')
  return raw.endsWith('/') ? raw.slice(0, -1) : raw
}

function getAuthHeader() {
  const username = getEnv('WORDPRESS_USERNAME')
  const appPassword = getEnv('WORDPRESS_APP_PASSWORD')
  const raw = `${username}:${appPassword}`
  const token =
    typeof Buffer !== 'undefined'
      ? Buffer.from(raw).toString('base64')
      : // eslint-disable-next-line @typescript-eslint/no-explicit-any
        (globalThis as any).btoa(raw)
  return `Basic ${token}`
}

async function wpFetch<T>(path: string, init?: RequestInit): Promise<T> {
  const baseUrl = getBaseUrl()
  const url = `${baseUrl}${path.startsWith('/') ? '' : '/'}${path}`

  const res = await fetch(url, {
    ...init,
    headers: {
      Authorization: getAuthHeader(),
      ...(init?.headers ?? {}),
    },
    cache: 'no-store',
  })

  if (!res.ok) {
    const text = await res.text().catch(() => '')
    const wpMsg = tryExtractWpMessage(text)
    throw new WPError(
      `WP ${res.status} ${res.statusText} (${url})${wpMsg ? ` ${wpMsg}` : ''}`,
      res.status,
      text,
    )
  }

  return (await res.json()) as T
}

function toWpPostPayload(data: Partial<WPPost>) {
  return {
    title: data.title?.raw ?? data.title?.rendered,
    content: data.content?.raw ?? data.content?.rendered,
    excerpt: data.excerpt?.raw ?? data.excerpt?.rendered,
    status: data.status,
    slug: data.slug,
    featured_media: data.featured_media,
    categories: data.categories,
    tags: data.tags,
    meta: data.meta,
  }
}

export const wordpressClient = {
  async getAllPosts(): Promise<WPPost[]> {
    try {
      return await wpFetch<WPPost[]>(
        `/wp-json/wp/v2/posts?context=edit&status=publish,draft&per_page=100&orderby=date&order=desc`
      )
    } catch (e) {
      if (e instanceof Error && e.message.includes('rest_forbidden_context')) {
        throw new Error(
          'WordPress API taslakları listeleyemiyor (rest_forbidden_context). WORDPRESS_USERNAME / WORDPRESS_APP_PASSWORD yetkisini kontrol et.'
        )
      }
      throw e
    }
  },

  async getPostById(id: number): Promise<WPPost | null> {
    try {
      return await wpFetch<WPPost>(`/wp-json/wp/v2/posts/${id}?context=edit`)
    } catch (e) {
      if (e instanceof Error && e.message.includes('rest_forbidden_context')) {
        return await wpFetch<WPPost>(`/wp-json/wp/v2/posts/${id}?context=view`)
      }
      if (e instanceof Error && e.message.includes('WP 404')) return null
      throw e
    }
  },

  async createPost(data: Partial<WPPost>): Promise<WPPost> {
    return wpFetch<WPPost>(`/wp-json/wp/v2/posts`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(toWpPostPayload(data)),
    })
  },

  async updatePost(id: number, data: Partial<WPPost>): Promise<WPPost | null> {
    try {
      return await wpFetch<WPPost>(`/wp-json/wp/v2/posts/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(toWpPostPayload(data)),
      })
    } catch (e) {
      if (e instanceof Error && e.message.includes('WP 404')) return null
      throw e
    }
  },

  async deletePost(id: number): Promise<boolean> {
    try {
      await wpFetch<unknown>(`/wp-json/wp/v2/posts/${id}?force=true`, { method: 'DELETE' })
      return true
    } catch (e) {
      if (e instanceof Error && e.message.includes('WP 404')) return false
      throw e
    }
  },

  async publishPost(id: number): Promise<WPPost | null> {
    return this.updatePost(id, { status: 'publish' } as Partial<WPPost>)
  },

  async uploadMedia(file: File): Promise<{ id: number; url: string }> {
    const baseUrl = getBaseUrl()
    const url = `${baseUrl}/wp-json/wp/v2/media`
    const arrayBuffer = await file.arrayBuffer()

    const res = await fetch(url, {
      method: 'POST',
      headers: {
        Authorization: getAuthHeader(),
        'Content-Disposition': `attachment; filename="${encodeURIComponent(file.name)}"`,
        'Content-Type': file.type || 'application/octet-stream',
      },
      body: Buffer.from(arrayBuffer),
    })

    if (!res.ok) {
      const text = await res.text().catch(() => '')
      throw new Error(`WP ${res.status} ${res.statusText} (${url}) ${text}`)
    }

    const data = (await res.json()) as { id: number; source_url?: string }
    return { id: data.id, url: data.source_url ?? '' }
  },
}

export async function publishToWordPress(data: {
  title: string;
  content: string;
  excerpt: string;
  status: 'draft' | 'publish';
}): Promise<{ success: boolean; postId?: number; error?: string }> {
  try {
    const post = await wordpressClient.createPost({
      title: { raw: data.title },
      content: { raw: data.content },
      excerpt: { raw: data.excerpt },
      status: data.status,
    } as Partial<WPPost>);

    return {
      success: true,
      postId: post.id,
    };
  } catch (error) {
    console.error('WordPress publish error:', error);
    return {
      success: false,
      error: error instanceof Error ? error.message : 'Unknown error',
    };
  }
}
