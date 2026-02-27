import PostEditor from '@/components/posts/PostEditor'

export default async function EditPostPage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = await params
  return <PostEditor mode="edit" id={Number(id)} />
}
