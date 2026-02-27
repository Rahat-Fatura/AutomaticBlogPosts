import { cookies } from 'next/headers'

export const runtime = 'nodejs'

const COOKIE_NAME = 'admin_session'

export async function POST() {
  const cookieStore = await cookies()
  cookieStore.set({
    name: COOKIE_NAME,
    value: '',
    httpOnly: true,
    sameSite: 'lax',
    secure: process.env.NODE_ENV === 'production',
    path: '/',
    maxAge: 0,
  })

  return Response.json({ ok: true })
}
