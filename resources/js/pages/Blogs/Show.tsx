import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import { Head, useForm } from '@inertiajs/react';
import { MessageCircle, Share2, ThumbsDown, ThumbsUp } from 'lucide-react';
import { FormEvent, useState } from 'react';

interface Comment {
    id: number;
    content: string;
    user: {
        name: string;
    };
    commenter_id: string;
    created_at: string;
    replies?: Comment[];
}

interface BlogDetailProps {
    blog: {
        id: number;
        title: string;
        content: string;
        owner: {
            name: string;
            avatar?: string;
        };
        tags: Array<{ id: number; name: string }>;
        comments: Comment[];
        reactions: Array<{ type: 'like' | 'dislike' }>;
        created_at: string;
    };
}

export default function BlogDetail({ blog }: BlogDetailProps) {
    const { data, setData, post, processing, errors } = useForm({
        content: '',
        parent_id: null as number | null,
    });

    const [replyingTo, setReplyingTo] = useState<number | null>(null);

    const handleCommentSubmit = (e: FormEvent<HTMLFormElement>, parentId?: number) => {
        e.preventDefault();

        if (parentId) {
            setData('parent_id', parentId);
        }

        post(`/blogs/${blog.id}/comments`, {
            onSuccess: () => {
                setData('content', '');
                setReplyingTo(null);
            },
            onError: (err: Record<string, string>) => {
                console.error('Comment submission error:', err);
            },
        });
    };

    const renderComments = (comments: Comment[], level = 0) => {
        return comments.map((comment) => (
            <div key={comment.id} className={`border-l-4 ${level > 0 ? 'pl-4' : 'pl-0'} py-4`}>
                <div className="mb-2 flex items-center">
                    <span className="mr-2 font-semibold">{comment.commenter_id}</span>
                    <span className="text-muted-foreground text-sm">{new Date(comment.created_at).toLocaleString()}</span>
                    {level < 2 && (
                        <Button variant="ghost" size="sm" className="ml-2" onClick={() => setReplyingTo(comment.id)}>
                            Reply
                        </Button>
                    )}
                </div>
                <p>{comment.content}</p>

                {comment.replies && comment.replies.length > 0 && <div className="mt-4">{renderComments(comment.replies, level + 1)}</div>}

                {replyingTo === comment.id && (
                    <form onSubmit={(e) => handleCommentSubmit(e, comment.id)} className="mt-4">
                        <Textarea
                            placeholder={`Reply to ${comment.commenter_id}...`}
                            value={data.content}
                            onChange={(e) => setData('content', e.target.value)}
                            required
                            className="mb-2"
                        />
                        {errors.content && <p className="text-sm text-red-500">{errors.content}</p>}
                        <div className="flex space-x-2">
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Posting...' : 'Post Reply'}
                            </Button>
                            <Button type="button" variant="secondary" onClick={() => setReplyingTo(null)}>
                                Cancel
                            </Button>
                        </div>
                    </form>
                )}
            </div>
        ));
    };

    const likeCount = blog.reactions.filter((r) => r.type === 'like').length;
    const dislikeCount = blog.reactions.filter((r) => r.type === 'dislike').length;

    return (
        <div className="container mx-auto px-4 py-8">
            <Head title={blog.title} />

            <Card className="mx-auto max-w-4xl">
                <CardHeader className="flex flex-col items-start">
                    <div className="mb-4 flex items-center">
                        {blog.owner.avatar && <img src={blog.owner.avatar} alt={blog.owner.name} className="mr-4 h-12 w-12 rounded-full" />}
                        <div>
                            <CardTitle>{blog.title}</CardTitle>
                            <p className="text-muted-foreground text-sm">
                                By {blog.owner.name} on {new Date(blog.created_at).toLocaleDateString()}
                            </p>
                        </div>
                    </div>

                    <div className="mb-4 flex gap-2">
                        {blog.tags.map((tag) => (
                            <Badge key={tag.id} variant="secondary">
                                {tag.name}
                            </Badge>
                        ))}
                    </div>
                </CardHeader>

                <CardContent>
                    <article className="prose lg:prose-xl">{blog.content}</article>

                    <div className="mt-6 flex items-center space-x-4">
                        <Button variant="outline" size="sm">
                            <ThumbsUp className="mr-2 h-4 w-4" /> Like ({likeCount})
                        </Button>
                        <Button variant="outline" size="sm">
                            <ThumbsDown className="mr-2 h-4 w-4" /> Dislike ({dislikeCount})
                        </Button>
                        <Button variant="outline" size="sm">
                            <Share2 className="mr-2 h-4 w-4" /> Share
                        </Button>
                    </div>

                    <div className="mt-8">
                        <h3 className="mb-4 flex items-center text-xl font-semibold">
                            <MessageCircle className="mr-2 h-6 w-6" />
                            Comments ({blog.comments.length})
                        </h3>

                        <form onSubmit={handleCommentSubmit} className="mb-6">
                            <Textarea
                                placeholder="Write a comment..."
                                value={data.content}
                                onChange={(e) => setData('content', e.target.value)}
                                required
                                className="mb-2"
                            />
                            {errors.content && <p className="text-sm text-red-500">{errors.content}</p>}
                            <Button type="submit" disabled={processing} className="w-full">
                                {processing ? 'Posting...' : 'Post Comment'}
                            </Button>
                        </form>

                        {blog.comments.length === 0 ? <p className="text-muted-foreground">No comments yet</p> : renderComments(blog.comments)}
                    </div>
                </CardContent>
            </Card>
        </div>
    );
}
