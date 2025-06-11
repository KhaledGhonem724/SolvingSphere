import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { router } from '@inertiajs/core';
import { Head, useForm, usePage } from '@inertiajs/react';
import { Check, Edit, MessageCircle, Share2, ThumbsDown, ThumbsUp, Trash2 } from 'lucide-react';
import { FormEvent, useState } from 'react';

interface Comment {
    id: number;
    content: string;
    user: {
        user_handle: string;
    };
    commenter_id: string;
    created_at: string;
    parent_id?: number;
    replies?: Comment[];
}

interface BlogReaction {
    id: number;
    value: number; // 1 for like, -1 for dislike
    user_id: string;
    blog_id: number;
}

interface BlogDetailProps {
    blog: {
        id: number;
        title: string;
        content: string;
        blog_type: string;
        score: number;
        owner: {
            user_handle: string;
            avatar?: string;
        };
        owner_id: string;
        tags: Array<{ id: number; name: string }>;
        comments: Comment[];
        reactions: BlogReaction[];
        created_at: string;
    };
}

interface User {
    user_handle: string;
}

export default function BlogDetail({ blog }: BlogDetailProps) {
    const { auth } = usePage().props as { auth: { user: User } };
    const user = auth?.user;

    const { data, setData, post, processing, errors, reset } = useForm({
        content: '',
        parent_id: null as number | null,
    });

    // Form for comment editing
    const {
        data: editData,
        setData: setEditData,
        put: putComment,
        processing: editProcessing,
    } = useForm({
        content: '',
    });

    const [replyingTo, setReplyingTo] = useState<number | null>(null);
    const [editingComment, setEditingComment] = useState<number | null>(null);
    const [shareStatus, setShareStatus] = useState<'idle' | 'copied' | 'shared'>('idle');
    const [collapsedThreads, setCollapsedThreads] = useState<Set<number>>(new Set());

    // Get current user's reaction
    const userReaction = user ? blog.reactions.find((r) => r.user_id === user.user_handle) : null;
    const userLiked = userReaction?.value === 1;
    const userDisliked = userReaction?.value === -1;

    // Calculate like/dislike counts
    const likeCount = blog.reactions.filter((r) => r.value === 1).length;
    const dislikeCount = blog.reactions.filter((r) => r.value === -1).length;

    // Check if current user is the blog owner
    const isOwner = user?.user_handle === blog.owner_id;

    const handleReaction = (value: number) => {
        if (!user) return;

        router.post(
            `/blogs/${blog.id}/react`,
            {
                value: value,
            },
            {
                preserveScroll: true,
                onError: (errors: Record<string, string>) => {
                    console.error('Reaction error:', errors);
                },
            },
        );
    };

    const handleShare = async () => {
        const url = window.location.href;
        const title = blog.title;

        // Try native sharing first (mobile devices)
        if (navigator.share) {
            try {
                await navigator.share({
                    title: title,
                    text: `Check out this blog: ${title}`,
                    url: url,
                });
                setShareStatus('shared');
            } catch {
                // User cancelled or error occurred, fallback to clipboard
                await fallbackToClipboard(url);
            }
        } else {
            // Fallback to clipboard for desktop
            await fallbackToClipboard(url);
        }
    };

    const fallbackToClipboard = async (url: string) => {
        try {
            await navigator.clipboard.writeText(url);
            setShareStatus('copied');
            setTimeout(() => setShareStatus('idle'), 2000);
        } catch {
            console.error('Failed to copy to clipboard');
        }
    };

    // Helper function for form submissions with method spoofing
    const submitDeleteForm = (url: string) => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    };

    const handleDeleteBlog = () => {
        if (confirm('Are you sure you want to delete this blog? This action cannot be undone.')) {
            submitDeleteForm(`/blogs/${blog.id}`);
        }
    };

    const handleCommentSubmit = (e: FormEvent<HTMLFormElement>, parentId?: number) => {
        e.preventDefault();

        post(`/blogs/${blog.id}/comments`, {
            data: {
                content: data.content,
                parent_id: parentId || null,
            },
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setReplyingTo(null);
            },
            onError: (err: Record<string, string>) => {
                console.error('Comment submission error:', err);
            },
        });
    };

    const handleUpdateComment = (e: FormEvent<HTMLFormElement>, commentId: number) => {
        e.preventDefault();

        putComment(`/comments/${commentId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setEditingComment(null);
                setEditData('content', '');
            },
        });
    };

    const handleDeleteComment = (commentId: number) => {
        if (confirm('Are you sure you want to delete this comment?')) {
            submitDeleteForm(`/comments/${commentId}`);
        }
    };
    /* eslint-disable */
    const toggleThread = (commentId: number) => {
        const newCollapsed = new Set(collapsedThreads);
        if (newCollapsed.has(commentId)) {
            newCollapsed.delete(commentId);
        } else {
            newCollapsed.add(commentId);
        }
        setCollapsedThreads(newCollapsed);
    };

    const handleReply = (commentId: number) => {
        setReplyingTo(replyingTo === commentId ? null : commentId);
        setEditingComment(null); // Close any edit forms
    };

    const handleEdit = (comment: Comment) => {
        setEditingComment(comment.id);
        setEditData('content', comment.content);
        setReplyingTo(null); // Close any reply forms
    };

    const cancelEdit = () => {
        setEditingComment(null);
        setEditData('content', '');
    };

    const cancelReply = () => {
        setReplyingTo(null);
        reset();
    };

    const getTimeAgo = (dateString: string): string => {
        const now = new Date();
        const date = new Date(dateString);
        const diffInMs = now.getTime() - date.getTime();
        const diffInMinutes = Math.floor(diffInMs / (1000 * 60));
        const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
        const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

        if (diffInMinutes < 1) return 'now';
        if (diffInMinutes < 60) return `${diffInMinutes}m ago`;
        if (diffInHours < 24) return `${diffInHours}h ago`;
        if (diffInDays < 7) return `${diffInDays}d ago`;

        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined,
        });
    };

    // Helper function to count total comments including replies
    const countTotalComments = (comments: Comment[]): number => {
        return comments.reduce((total, comment) => {
            return total + 1 + (comment.replies ? countTotalComments(comment.replies) : 0);
        }, 0);
    };

    const totalCommentsCount = countTotalComments(blog.comments);

    // Simplified and improved Comment Component
    const CommentComponent = ({ comment, depth = 0 }: { comment: Comment; depth?: number }) => {
        const isCommentOwner = user?.user_handle === comment.commenter_id;
        const hasReplies = comment.replies && comment.replies.length > 0;

        return (
            <div className={`relative py-4 ${depth > 0 ? 'border-l border-gray-200 pl-8 dark:border-gray-800' : ''}`}>
                <div className="flex items-start space-x-3">
                    {/* User Avatar */}
                    <div className="flex-shrink-0">
                        <div className="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-800">
                            <span className="text-sm font-semibold text-gray-600 dark:text-gray-300">
                                {comment.user.user_handle.charAt(0).toUpperCase()}
                            </span>
                        </div>
                    </div>

                    {/* Comment Content */}
                    <div className="min-w-0 flex-1">
                        {editingComment === comment.id ? (
                            <form onSubmit={(e) => handleUpdateComment(e, comment.id)} className="space-y-2">
                                <Textarea
                                    ref={(input) => {
                                        if (input) input.focus();
                                    }}
                                    value={editData.content}
                                    onChange={(e) => {
                                        setEditData('content', e.target.value);
                                    }}
                                    onKeyDown={(e) => {
                                        // Allow continuing to type without losing focus
                                        if (e.key === 'Escape') {
                                            setEditingComment(null);
                                            setEditData('content', '');
                                        }
                                    }}
                                    required
                                    autoFocus
                                    className="min-h-[80px] border-gray-300 bg-white text-gray-900 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100"
                                />
                                <div className="flex space-x-2">
                                    <Button
                                        type="submit"
                                        disabled={editProcessing}
                                        className="bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-800 dark:hover:bg-blue-700"
                                    >
                                        {editProcessing ? 'Updating...' : 'Update'}
                                    </Button>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => {
                                            setEditingComment(null);
                                            setEditData('content', '');
                                        }}
                                        className="border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                                    >
                                        Cancel
                                    </Button>
                                </div>
                            </form>
                        ) : (
                            <>
                                <div className="mb-2 flex items-center justify-between">
                                    <div className="flex items-center space-x-2">
                                        <span className="text-sm font-semibold text-gray-800 dark:text-gray-200">{comment.user.user_handle}</span>
                                        <span className="text-xs text-gray-500 dark:text-gray-400">{getTimeAgo(comment.created_at)}</span>
                                    </div>

                                    {/* Comment Actions */}
                                    {isCommentOwner && (
                                        <div className="flex items-center space-x-1">
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                className="h-7 w-7 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400"
                                                onClick={() => {
                                                    setEditingComment(comment.id);
                                                    setEditData('content', comment.content);
                                                    setReplyingTo(null);
                                                }}
                                            >
                                                <Edit className="h-4 w-4" />
                                            </Button>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                className="h-7 w-7 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400"
                                                onClick={() => handleDeleteComment(comment.id)}
                                            >
                                                <Trash2 className="h-4 w-4" />
                                            </Button>
                                        </div>
                                    )}
                                </div>

                                {/* Comment Text */}
                                <p className="text-sm leading-relaxed whitespace-pre-wrap text-gray-700 dark:text-gray-300">{comment.content}</p>

                                {/* Comment Actions */}
                                <div className="mt-2 flex items-center space-x-2">
                                    {user && (
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            className="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400"
                                            onClick={() => handleReply(comment.id)}
                                        >
                                            <MessageCircle className="mr-1 h-4 w-4" />
                                            Reply
                                        </Button>
                                    )}
                                </div>
                            </>
                        )}

                        {/* Reply Form */}
                        {replyingTo === comment.id && (
                            <div className="mt-4 border-l border-gray-200 pl-8 dark:border-gray-800">
                                <form onSubmit={(e) => handleCommentSubmit(e, comment.id)} className="space-y-2">
                                    <Textarea
                                        placeholder={`Reply to ${comment.user.user_handle}...`}
                                        value={data.content}
                                        onChange={(e) => setData('content', e.target.value)}
                                        required
                                        className="min-h-[80px] border-gray-300 bg-white text-gray-900 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100"
                                    />
                                    {errors.content && <p className="mt-1 text-sm text-red-500 dark:text-red-400">{errors.content}</p>}
                                    <div className="flex space-x-2">
                                        <Button
                                            type="submit"
                                            disabled={processing}
                                            className="bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-800 dark:hover:bg-blue-700"
                                        >
                                            {processing ? 'Posting...' : 'Post Reply'}
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={() => setReplyingTo(null)}
                                            className="border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                                        >
                                            Cancel
                                        </Button>
                                    </div>
                                </form>
                            </div>
                        )}

                        {/* Nested Replies */}
                        {hasReplies && (
                            <div className="mt-4">
                                {comment.replies?.map((reply) => <CommentComponent key={reply.id} comment={reply} depth={depth + 1} />)}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        );
    };

    // Render comments with improved styling
    const renderComments = (comments: Comment[]) => {
        if (!comments || comments.length === 0) {
            return (
                <div className="rounded-lg bg-gray-50 py-12 text-center dark:bg-gray-900/50">
                    <MessageCircle className="mx-auto mb-4 h-12 w-12 text-gray-400 dark:text-gray-600" />
                    <p className="text-lg text-gray-500 dark:text-gray-400">No comments yet. Be the first to comment!</p>
                </div>
            );
        }

        return (
            <div className="divide-y divide-gray-200 dark:divide-gray-800">
                {comments.map((comment) => (
                    <CommentComponent key={comment.id} comment={comment} depth={0} />
                ))}
            </div>
        );
    };

    return (
        <AppSidebarLayout
            key={user?.user_handle || 'anonymous'}
            breadcrumbs={[
                { title: 'Blogs', href: '/blogs' },
                { title: blog.title, href: `/blogs/${blog.id}` },
            ]}
        >
            <div className="container mx-auto max-w-4xl px-4 py-6">
                <Head title={blog.title} />

                <Card className="overflow-hidden rounded-xl border bg-white shadow-md dark:border-gray-800 dark:bg-gray-900 dark:shadow-xl">
                    <CardHeader className="border-b bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-900/50">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center space-x-4">
                                {blog.owner.avatar ? (
                                    <img
                                        src={blog.owner.avatar}
                                        alt={blog.owner.user_handle}
                                        className="h-12 w-12 rounded-full border-2 border-gray-200 object-cover dark:border-gray-700"
                                    />
                                ) : (
                                    <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-800">
                                        <span className="font-semibold text-gray-600 dark:text-gray-300">
                                            {blog.owner.user_handle.charAt(0).toUpperCase()}
                                        </span>
                                    </div>
                                )}
                                <div>
                                    <h1 className="mb-1 text-2xl font-bold text-gray-900 dark:text-gray-50">{blog.title}</h1>
                                    <div className="flex items-center space-x-2">
                                        <Badge
                                            variant="outline"
                                            className="border-gray-300 bg-white text-gray-700 capitalize dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                                        >
                                            {blog.blog_type}
                                        </Badge>
                                        <span className="text-sm text-gray-500 dark:text-gray-400">
                                            By {blog.owner.user_handle} on {new Date(blog.created_at).toLocaleDateString()}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {isOwner && (
                                <div className="flex space-x-2">
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        className="border-gray-300 bg-white text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
                                        onClick={() => router.visit(`/blogs/${blog.id}/edit`)}
                                    >
                                        <Edit className="mr-2 h-4 w-4" />
                                        Edit
                                    </Button>
                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        className="bg-red-600 text-white hover:bg-red-700 dark:bg-red-800 dark:hover:bg-red-700"
                                        onClick={handleDeleteBlog}
                                    >
                                        <Trash2 className="mr-2 h-4 w-4" />
                                        Delete
                                    </Button>
                                </div>
                            )}
                        </div>

                        {blog.tags.length > 0 && (
                            <div className="mt-4 flex gap-2">
                                {blog.tags.map((tag) => (
                                    <Badge key={tag.id} variant="secondary" className="bg-gray-200 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                                        {tag.name}
                                    </Badge>
                                ))}
                            </div>
                        )}
                    </CardHeader>

                    <CardContent className="p-6">
                        <article className="prose prose-lg dark:prose-invert mb-6 max-w-none leading-relaxed text-gray-800 dark:text-gray-100">
                            {blog.content}
                        </article>

                        <div className="mb-6 flex flex-col items-center space-y-4">
                            <div className="text-3xl font-bold text-gray-700 dark:text-gray-200">
                                Score:{' '}
                                <span className={blog.score >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}>
                                    {blog.score}
                                </span>
                            </div>

                            <div className="flex items-center space-x-4">
                                <Button
                                    variant={userLiked ? 'default' : 'outline'}
                                    size="lg"
                                    onClick={() => handleReaction(1)}
                                    disabled={!user}
                                    className={` ${
                                        userLiked
                                            ? 'bg-green-600 text-white hover:bg-green-700'
                                            : 'border-gray-300 bg-white text-gray-700 hover:bg-green-50 hover:text-green-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-green-900/20 dark:hover:text-green-400'
                                    } `}
                                >
                                    <ThumbsUp className="mr-2 h-5 w-5" />
                                    Like ({likeCount})
                                </Button>

                                <Button
                                    variant={userDisliked ? 'default' : 'outline'}
                                    size="lg"
                                    onClick={() => handleReaction(-1)}
                                    disabled={!user}
                                    className={` ${
                                        userDisliked
                                            ? 'bg-red-600 text-white hover:bg-red-700'
                                            : 'border-gray-300 bg-white text-gray-700 hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-red-900/20 dark:hover:text-red-400'
                                    } `}
                                >
                                    <ThumbsDown className="mr-2 h-5 w-5" />
                                    Dislike ({dislikeCount})
                                </Button>

                                <Button
                                    variant="outline"
                                    size="lg"
                                    onClick={handleShare}
                                    className="border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
                                >
                                    {shareStatus === 'copied' ? (
                                        <>
                                            <Check className="mr-2 h-5 w-5 text-green-600 dark:text-green-400" /> Copied!
                                        </>
                                    ) : shareStatus === 'shared' ? (
                                        <>
                                            <Check className="mr-2 h-5 w-5 text-green-600 dark:text-green-400" /> Shared!
                                        </>
                                    ) : (
                                        <>
                                            <Share2 className="mr-2 h-5 w-5" /> Share
                                        </>
                                    )}
                                </Button>
                            </div>
                        </div>

                        {!user && (
                            <Alert className="mb-6 border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-900/30">
                                <AlertDescription className="text-blue-800 dark:text-blue-200">
                                    Please log in to like, dislike, or comment on this blog.
                                </AlertDescription>
                            </Alert>
                        )}

                        <div className="mt-8 border-t pt-6 dark:border-gray-800">
                            <h2 className="mb-4 flex items-center text-2xl font-bold text-gray-900 dark:text-gray-50">
                                <MessageCircle className="mr-3 h-7 w-7 text-gray-600 dark:text-gray-400" />
                                Comments ({totalCommentsCount})
                            </h2>

                            {user && (
                                <div className="mb-6 rounded-lg border bg-gray-100 p-4 dark:border-gray-800 dark:bg-gray-900/50">
                                    <form onSubmit={handleCommentSubmit} className="space-y-2">
                                        <Textarea
                                            placeholder="Add a comment..."
                                            value={data.content}
                                            onChange={(e) => setData('content', e.target.value)}
                                            required
                                            className="min-h-[80px] border-gray-300 bg-white text-gray-900 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100"
                                        />
                                        {errors.content && <p className="mt-1 text-sm text-red-500 dark:text-red-400">{errors.content}</p>}
                                        <div className="flex justify-end">
                                            <Button
                                                type="submit"
                                                disabled={processing}
                                                className="bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-800 dark:hover:bg-blue-700"
                                            >
                                                {processing ? 'Posting...' : 'Post Comment'}
                                            </Button>
                                        </div>
                                    </form>
                                </div>
                            )}

                            <div>{renderComments(blog.comments)}</div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppSidebarLayout>
    );
}
