import { wordpressClient } from '@/lib/wordpress'
import type { WPPost } from '@/lib/types'

export const runtime = 'nodejs'

export async function GET() {
  try {
    const posts = await wordpressClient.getAllPosts()
    return Response.json(posts)
  } catch (e) {
    const status = e && typeof e === 'object' && 'status' in e ? Number((e as { status: unknown }).status) : 500
    const message = e instanceof Error ? e.message : 'Unknown error'
    return Response.json({ error: message }, { status: Number.isFinite(status) ? status : 500 })
  }
}

export async function POST(req: Request) {
  try {
    const body = (await req.json()) as Partial<WPPost>
    const post = await wordpressClient.createPost(body)
    return Response.json(post, { status: 201 })
  } catch (e) {
    const status = e && typeof e === 'object' && 'status' in e ? Number((e as { status: unknown }).status) : 500
    const message = e instanceof Error ? e.message : 'Unknown error'
    return Response.json({ error: message }, { status: Number.isFinite(status) ? status : 500 })
  }
}
