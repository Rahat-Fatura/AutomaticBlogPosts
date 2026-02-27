import { wordpressClient } from '@/lib/wordpress'

export const runtime = 'nodejs'

export async function POST(_req: Request, context: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await context.params
    const postId = Number(id)

    if (Number.isNaN(postId)) {
      return Response.json({ error: 'Invalid id' }, { status: 400 })
    }

    const post = await wordpressClient.publishPost(postId)
    if (!post) return Response.json({ error: 'Not found' }, { status: 404 })
    return Response.json({ message: 'Published', post })
  } catch (e) {
    const status = e && typeof e === 'object' && 'status' in e ? Number((e as { status: unknown }).status) : 500
    const message = e instanceof Error ? e.message : 'Unknown error'
    return Response.json({ error: message }, { status: Number.isFinite(status) ? status : 500 })
  }
}
