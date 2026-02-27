import { wordpressClient } from '@/lib/wordpress'

export const runtime = 'nodejs'

export async function POST(req: Request) {
  try {
    const formData = await req.formData()
    const file = formData.get('file')

    if (!(file instanceof File)) {
      return Response.json({ error: 'file is required' }, { status: 400 })
    }

    const result = await wordpressClient.uploadMedia(file)
    return Response.json(result)
  } catch (e) {
    const status = e && typeof e === 'object' && 'status' in e ? Number((e as { status: unknown }).status) : 500
    const message = e instanceof Error ? e.message : 'Unknown error'
    return Response.json({ error: message }, { status: Number.isFinite(status) ? status : 500 })
  }
}
