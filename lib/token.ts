import jwt from 'jsonwebtoken';

const JWT_SECRET = process.env.JWT_SECRET || 'fallback-secret-key';

export interface TokenPayload {
  draftId: string;
  action: 'approve' | 'reject';
  createdAt: number;
}

export function generateApprovalToken(draftId: string): string {
  const payload: TokenPayload = {
    draftId,
    action: 'approve',
    createdAt: Date.now(),
  };

  return jwt.sign(payload, JWT_SECRET, {
    expiresIn: '7d',
  });
}

export function generateRejectionToken(draftId: string): string {
  const payload: TokenPayload = {
    draftId,
    action: 'reject',
    createdAt: Date.now(),
  };

  return jwt.sign(payload, JWT_SECRET, {
    expiresIn: '7d',
  });
}

export function verifyToken(token: string): TokenPayload | null {
  try {
    const decoded = jwt.verify(token, JWT_SECRET) as TokenPayload;
    return decoded;
  } catch (error) {
    console.error('Token verification failed:', error);
    return null;
  }
}
