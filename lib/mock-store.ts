import { WPPost } from './types'

let posts: WPPost[] = [
  {
    id: 1,
    title: { rendered: 'İlk Blog Yazımız', raw: 'İlk Blog Yazımız' },
    content: { rendered: '<p>Bu deneme içeriğidir.</p>', raw: '<p>Bu deneme içeriğidir.</p>' },
    excerpt: { rendered: 'Kısa özet...', raw: 'Kısa özet...' },
    status: 'draft',
    slug: 'ilk-blog-yazimiz',
    date: new Date().toISOString(),
    featured_media: 0,
    categories: [],
    tags: [],
  },
  {
    id: 2,
    title: { rendered: 'Yayında Olan Yazı', raw: 'Yayında Olan Yazı' },
    content: { rendered: '<p>Bu yazı yayında.</p>', raw: '<p>Bu yazı yayında.</p>' },
    excerpt: { rendered: 'Yayında özet...', raw: 'Yayında özet...' },
    status: 'publish',
    slug: 'yayinda-olan-yazi',
    date: new Date().toISOString(),
    featured_media: 0,
    categories: [],
    tags: [],
  },
]

let nextId = 3

export const mockStore = {
  getAll: () => [...posts],
  getById: (id: number) => posts.find((p) => p.id === id) ?? null,
  create: (data: Partial<WPPost>): WPPost => {
    const post: WPPost = {
      id: nextId++,
      title: data.title ?? { rendered: '', raw: '' },
      content: data.content ?? { rendered: '', raw: '' },
      excerpt: data.excerpt ?? { rendered: '', raw: '' },
      status: 'draft',
      slug: data.slug ?? `yazi-${nextId}`,
      date: new Date().toISOString(),
      featured_media: 0,
      categories: [],
      tags: [],
    }
    posts.push(post)
    return post
  },
  update: (id: number, data: Partial<WPPost>): WPPost | null => {
    const index = posts.findIndex((p) => p.id === id)
    if (index === -1) return null
    posts[index] = { ...posts[index], ...data }
    return posts[index]
  },
  publish: (id: number): WPPost | null => {
    return mockStore.update(id, { status: 'publish' })
  },
  delete: (id: number): boolean => {
    const before = posts.length
    posts = posts.filter((p) => p.id !== id)
    return posts.length < before
  },
}
