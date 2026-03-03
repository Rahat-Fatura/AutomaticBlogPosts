export const runtime = 'nodejs'

function extractTextFromGeminiResponse(json: unknown): string {
  if (!json || typeof json !== 'object') return ''

  const candidates = (json as { candidates?: unknown }).candidates
  if (!Array.isArray(candidates) || candidates.length === 0) return ''

  const first = candidates[0] as {
    content?: {
      parts?: Array<{ text?: unknown }>
    }
  }

  const parts = first?.content?.parts
  if (!Array.isArray(parts) || parts.length === 0) return ''

  const text = parts
    .map((p) => (typeof p?.text === 'string' ? p.text : ''))
    .filter(Boolean)
    .join('')

  return text.trim()
}

export async function callGeminiGenerateContent({
  apiKey,
  model,
  prompt,
}: {
  apiKey: string
  model: string
  prompt: string
}): Promise<{ ok: true; text: string } | { ok: false; status: number; error: string }> {
  const url = `https://generativelanguage.googleapis.com/v1/models/${encodeURIComponent(model)}:generateContent?key=${encodeURIComponent(apiKey)}`

  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      contents: [
        {
          role: 'user',
          parts: [{ text: prompt }],
        },
      ],
      generationConfig: {
        temperature: 0.7,
        maxOutputTokens: 2048,
      },
    }),
  })

  const raw = (await res.json().catch(() => null)) as unknown

  if (!res.ok) {
    const structured =
      raw && typeof raw === 'object' && 'error' in raw
        ? (raw as {
            error?: {
              message?: unknown
              status?: unknown
              details?: unknown
            }
          }).error
        : null

    const msg =
      typeof structured?.message === 'string'
        ? structured.message
        : raw
          ? JSON.stringify(raw)
          : 'Gemini isteği başarısız.'

    const retryInfoSeconds = (() => {
      const details = structured?.details
      if (!Array.isArray(details)) return null
      const retryInfo = details.find(
        (d) =>
          d &&
          typeof d === 'object' &&
          '@type' in d &&
          (d as { '@type'?: unknown })['@type'] === 'type.googleapis.com/google.rpc.RetryInfo'
      ) as null | { retryDelay?: unknown }

      const retryDelay = retryInfo?.retryDelay
      if (typeof retryDelay !== 'string') return null
      const m = retryDelay.match(/^(\d+)s$/)
      if (!m) return null
      const s = Number(m[1])
      return Number.isFinite(s) ? s : null
    })()

    const withRetry = retryInfoSeconds != null ? `${msg} (Lütfen ${retryInfoSeconds}s sonra tekrar dene.)` : msg
    return { ok: false, status: res.status || 500, error: withRetry }
  }

  const text = extractTextFromGeminiResponse(raw)
  if (!text) {
    return { ok: false, status: 502, error: 'Gemini boş çıktı döndürdü.' }
  }

  return { ok: true, text }
}

export async function POST(req: Request) {
  try {
    const apiKey = process.env.GEMINI_API_KEY
    if (!apiKey) {
      return Response.json({ error: 'GEMINI_API_KEY eksik. .env.local içine ekleyin.' }, { status: 500 })
    }

    const body = (await req.json().catch(() => null)) as null | {
      prompt?: unknown
    }

    const prompt = typeof body?.prompt === 'string' ? body.prompt.trim() : ''
    if (!prompt) {
      return Response.json({ error: 'Prompt boş olamaz.' }, { status: 400 })
    }

    const preferredModel = (process.env.GEMINI_MODEL || '').trim()
    const candidates = [
      preferredModel,
      'gemini-2.5-flash',
      'gemini-2.5-pro',
      'gemini-1.5-flash',
      'gemini-1.5-pro',
      'gemini-2.0-flash',
    ].filter(Boolean)

    let lastError: { status: number; error: string } | null = null

    for (const model of candidates) {
      const out = await callGeminiGenerateContent({ apiKey, model, prompt })
      if (out.ok) return Response.json({ text: out.text })

      lastError = { status: out.status, error: out.error }

      const msg = String(out.error || '')
      const retryableNotFound = out.status === 404 || msg.includes('NOT_FOUND') || msg.includes('not found')
      const retryableUnsupported = msg.includes('not supported') || msg.includes('generateContent')

      if (!(retryableNotFound || retryableUnsupported)) break
    }

    return Response.json(
      {
        error:
          (lastError?.error || 'Gemini isteği başarısız.') +
          ' (Çözüm: .env.local içine GEMINI_MODEL ayarla veya Google AI Studio/Console’dan model listeni kontrol et.)',
      },
      { status: lastError?.status || 500 }
    )
  } catch (e) {
    const message = e instanceof Error ? e.message : 'Unknown error'
    return Response.json({ error: message }, { status: 500 })
  }
}
