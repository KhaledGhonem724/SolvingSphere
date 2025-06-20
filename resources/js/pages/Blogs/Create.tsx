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

interface CreateBlogProps {
    tags: Tag[];
}

const blogTypes = ['question', 'discussion', 'explain'];

export default function CreateBlog({ tags }: CreateBlogProps) {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        content: '',
        blog_type: blogTypes[0], // Default to first type
        tags: '',
    });

    const tagInputRef = useRef<HTMLInputElement>(null);
    const tagifyRef = useRef<Tagify | null>(null);

    useEffect(() => {
        if (tagInputRef.current) {
            // Destroy existing Tagify instance if it exists
            if (tagifyRef.current) {
                tagifyRef.current.destroy();
            }

            // Create new Tagify instance
            const tagify = new Tagify(tagInputRef.current, {
                whitelist: tags.map((tag) => tag.name),
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: 'tags-look',
                    enabled: 0,
                    closeOnSelect: false,
                },
                placeholder: 'Add tags',
            });

            // Add event listener to update form data when tags change
            tagify.on('change', (e: CustomEvent) => {
                const currentTags = e.detail.value;
                setData('tags', currentTags);
            });

            tagifyRef.current = tagify;
        }

        // Cleanup function
        return () => {
            if (tagifyRef.current) {
                tagifyRef.current.destroy();
            }
        };
    }, [tags]);

    const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post('/blogs', {
            onSuccess: () => {
                console.log('Blog created successfully');
            },
            onError: (errors: Record<string, string>) => {
                console.error('Blog creation errors:', errors);
            },
        });
    };

    return (
        <AppSidebarLayout>
            <div className="container mx-auto px-4 py-8">
                <Head title="Create New Blog" />

                <Card className="mx-auto max-w-2xl">
                    <CardHeader>
                        <CardTitle>Create a New Blog</CardTitle>
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
                                <Input id="tags" ref={tagInputRef} placeholder="Add tags" />
                                {errors.tags && <p className="mt-1 text-sm text-red-500">{errors.tags}</p>}
                            </div>

                            <Button type="submit" disabled={processing} className="w-full">
                                {processing ? 'Publishing...' : 'Publish Blog'}
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppSidebarLayout>
    );
}
