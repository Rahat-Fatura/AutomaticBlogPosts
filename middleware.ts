import { NextResponse, type NextRequest } from 'next/server'

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

async function verifySession(token: string) {
  const secret = process.env.ADMIN_SESSION_SECRET
  if (!secret) return false

  const parts = token.split('.')
  if (parts.length !== 3) return false

  const [username, ts, sig] = parts
  if (!username || !ts || !sig) return false

  const now = Date.now()
  const issuedAt = Number(ts)
  if (!Number.isFinite(issuedAt)) return false

  const maxAgeMs = 7 * 24 * 60 * 60 * 1000
  if (issuedAt > now + 5 * 60 * 1000) return false
  if (now - issuedAt > maxAgeMs) return false

  const expected = await hmacSha256(secret, `${username}.${ts}`)
  return expected === sig
}

export async function middleware(req: NextRequest) {
  const { pathname } = req.nextUrl

  if (
    pathname.startsWith('/_next') ||
    pathname.startsWith('/favicon') ||
    pathname.startsWith('/login') ||
    pathname.startsWith('/api/auth/login') ||
    pathname.startsWith('/api/auth/logout')
  ) {
    return NextResponse.next()
  }

  const token = req.cookies.get(COOKIE_NAME)?.value
  const ok = token ? await verifySession(token) : false

  if (ok) return NextResponse.next()

  if (pathname.startsWith('/api')) {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  }

  const url = req.nextUrl.clone()
  url.pathname = '/login'
  url.searchParams.set('next', pathname)
  return NextResponse.redirect(url)
}

export const config = {
  matcher: ['/((?!_next/static|_next/image|favicon.ico).*)'],
}
