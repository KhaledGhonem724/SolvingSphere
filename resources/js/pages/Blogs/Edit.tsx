import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { Head, useForm } from '@inertiajs/react';
import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';
import { FormEvent, useEffect, useRef } from 'react';

interface Tag {
    id: number;
    name: string;
}

interface Blog {
    id: number;
    title: string;
    content: string;
    blog_type: string;
    tags?: Tag[];
}

interface EditBlogProps {
    blog: Blog;
    tags: Tag[];
}

const blogTypes = ['question', 'discussion', 'explain'];

export default function EditBlog({ blog, tags }: EditBlogProps) {
    const { data, setData, put, processing, errors } = useForm({
        title: blog.title,
        content: blog.content,
        blog_type: blog.blog_type,
        tags: blog.tags ? blog.tags.map((tag) => tag.name) : [],
    });

    const tagInputRef = useRef<HTMLInputElement>(null);
    const tagifyRef = useRef<Tagify | null>(null);

    useEffect(() => {
        if (tagInputRef.current) {
            if (tagifyRef.current) {
                tagifyRef.current.destroy();
            }

            const tagify = new Tagify(tagInputRef.current, {
                whitelist: tags.map((tag) => tag.name),
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: 'tags-look',
                    enabled: 0,
                    closeOnSelect: false,
                },
                placeholder: 'Add or type tags',
            });

            // Preload existing tags
            /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
            (tagify as any).addTags(blog.tags?.map((tag) => tag.name) || []);

            tagify.on('change', (e: CustomEvent) => {
                try {
                    const raw = e.detail.value;
                    const tagValues =
                        typeof raw === 'string'
                            ? (JSON.parse(raw) as Array<{ value: string }>).map((tag) => tag.value)
                            : (raw as Array<{ value?: string } | string>).map((tag) => (typeof tag === 'string' ? tag : tag.value || ''));

                    setData('tags', tagValues);
                } catch (err) {
                    console.error('Error parsing tags', err);
                    setData('tags', []);
                }
            });

            tagifyRef.current = tagify;
        }

        return () => {
            if (tagifyRef.current) {
                tagifyRef.current.destroy();
            }
        };
    }, [tags]);

    const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        // Convert tags to a JSON string
        const tagsToSubmit = JSON.stringify(data.tags);

        put(`/blogs/${blog.id}`, {
            ...data,
            tags: tagsToSubmit,
            onSuccess: () => {
                console.log('Blog updated successfully');
            },
            onError: (errors: Record<string, string>) => {
                console.error('Blog update errors:', errors);
            },
        });
    };

    return (
        <AppSidebarLayout>
            <div className="container mx-auto px-4 py-8">
                <Head title={`Edit: ${blog.title}`} />

                <Card className="mx-auto max-w-2xl">
                    <CardHeader>
                        <CardTitle>Edit Blog</CardTitle>
                    </CardHeader>

                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div>
                                <Label htmlFor="title">Title</Label>
                                <Input
                                    id="title"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    placeholder="Enter blog title"
                                    required
                                />
                                {errors.title && <p className="mt-1 text-sm text-red-500">{errors.title}</p>}
                            </div>

                            <div>
                                <Label htmlFor="content">Content</Label>
                                <Textarea
                                    id="content"
                                    value={data.content}
                                    onChange={(e) => setData('content', e.target.value)}
                                    placeholder="Write your blog content here..."
                                    rows={6}
                                    required
                                />
                                {errors.content && <p className="mt-1 text-sm text-red-500">{errors.content}</p>}
                            </div>

                            <div>
                                <Label>Blog Type</Label>
                                <Select value={data.blog_type} onValueChange={(value) => setData('blog_type', value)}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select blog type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {blogTypes.map((type) => (
                                            <SelectItem key={type} value={type}>
                                                {type}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.blog_type && <p className="mt-1 text-sm text-red-500">{errors.blog_type}</p>}
                            </div>

                            <div>
                                <Label htmlFor="tags">Tags</Label>
                                <Input id="tags" ref={tagInputRef} placeholder="Add or type tags" />
                                {errors.tags && <p className="mt-1 text-sm text-red-500">{errors.tags}</p>}
                            </div>

                            <div className="flex gap-4">
                                <Button type="submit" disabled={processing} className="flex-1">
                                    {processing ? 'Updating...' : 'Update Blog'}
                                </Button>
                                <Button type="button" variant="outline" onClick={() => window.history.back()} className="flex-1">
                                    Cancel
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppSidebarLayout>
    );
}
