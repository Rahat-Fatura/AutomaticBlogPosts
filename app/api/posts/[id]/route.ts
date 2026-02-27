import { wordpressClient } from '@/lib/wordpress'
import type { WPPost } from '@/lib/types'

export const runtime = 'nodejs'

export async function GET(_req: Request, context: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await context.params
    const postId = Number(id)

    if (Number.isNaN(postId)) {
      return Response.json({ error: 'Invalid id' }, { status: 400 })
    }

    const post = await wordpressClient.getPostById(postId)
    if (!post) return Response.json({ error: 'Not found' }, { status: 404 })
    return Response.json(post)
  } catch (e) {
    const status = e && typeof e === 'object' && 'status' in e ? Number((e as { status: unknown }).status) : 500
    const message = e instanceof Error ? e.message : 'Unknown error'
    return Response.json({ error: message }, { status: Number.isFinite(status) ? status : 500 })
  }
}

export async function PUT(req: Request, context: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await context.params
    const postId = Number(id)

    if (Number.isNaN(postId)) {
      return Response.json({ error: 'Invalid id' }, { status: 400 })
    }

    const body = (await req.json()) as Partial<WPPost>
    const post = await wordpressClient.updatePost(postId, body)
    if (!post) return Response.json({ error: 'Not found' }, { status: 404 })
    return Response.json(post)
  } catch (e) {
    const status = e && typeof e === 'object' && 'status' in e ? Number((e as { status: unknown }).status) : 500
    const message = e instanceof Error ? e.message : 'Unknown error'
    return Response.json({ error: message }, { status: Number.isFinite(status) ? status : 500 })
  }
}

export async function DELETE(_req: Request, context: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await context.params
    const postId = Number(id)

    if (Number.isNaN(postId)) {
      return Response.json({ error: 'Invalid id' }, { status: 400 })
    }

    const ok = await wordpressClient.deletePost(postId)
    if (!ok) return Response.json({ error: 'Not found' }, { status: 404 })
    return Response.json({ ok: true })
  } catch (e) {
    const status = e && typeof e === 'object' && 'status' in e ? Number((e as { status: unknown }).status) : 500
    const message = e instanceof Error ? e.message : 'Unknown error'
    return Response.json({ error: message }, { status: Number.isFinite(status) ? status : 500 })
  }
}
