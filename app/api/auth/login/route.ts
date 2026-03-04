import { cookies } from 'next/headers'

export const runtime = 'nodejs'

const COOKIE_NAME = 'admin_session'

function base64UrlEncode(bytes: Uint8Array) {
  let binary = ''
  for (let i = 0; i < bytes.length; i++) binary += String.fromCharCode(bytes[i])
  const b64 = btoa(binary)
  return b64.replaceAll('+', '-').replaceAll('/', '_').replaceAll('=', '')
}

async function hmacSha256(secret: string, data: string) {
  const enc = new TextEncoder()
  const key = await crypto.subtle.importKey(
    'raw',
    enc.encode(secret),
    { name: 'HMAC', hash: 'SHA-256' },
    false,
    ['sign'],
  )
  const sig = await crypto.subtle.sign('HMAC', key, enc.encode(data))
  return base64UrlEncode(new Uint8Array(sig))
}

export async function POST(req: Request) {
  const ADMIN_USERNAME = process.env.ADMIN_USERNAME
  const ADMIN_PASSWORD = process.env.ADMIN_PASSWORD
  const SECRET = process.env.ADMIN_SESSION_SECRET

  if (!ADMIN_USERNAME || !ADMIN_PASSWORD || !SECRET) {
    return Response.json({ error: 'Server auth env is missing.' }, { status: 500 })
  }

  const body = (await req.json().catch(() => null)) as { username?: string; password?: string } | null
  const username = body?.username || ''
  const password = body?.password || ''

  if (username !== ADMIN_USERNAME || password !== ADMIN_PASSWORD) {
    return Response.json({ error: 'Kullanıcı adı veya şifre hatalı.' }, { status: 401 })
  }

  const issuedAt = Date.now().toString()
  const sig = await hmacSha256(SECRET, `${username}.${issuedAt}`)
  const token = `${username}.${issuedAt}.${sig}`

  const cookieStore = await cookies()
  cookieStore.set({
    name: COOKIE_NAME,
    value: token,
    httpOnly: true,
    sameSite: 'lax',
    secure: process.env.COOKIE_SECURE !== 'false' && process.env.NODE_ENV === 'production',
    path: '/',
    maxAge: 7 * 24 * 60 * 60,
  })

  return Response.json({ ok: true })
}
