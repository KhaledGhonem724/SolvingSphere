import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

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
        tags: [] as number[],
    });

    const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post('/blogs', {
            onSuccess: () => {
                // Optional: Add toast or notification
                console.log('Blog created successfully');
            },
            onError: (errors: Record<string, string>) => {
                console.error('Blog creation errors:', errors);
                // Optional: Add error toast or display errors
            },
        });
    };

    const handleTagChange = (tagId: number) => {
        setData('tags', data.tags.includes(tagId) ? data.tags.filter((id) => id !== tagId) : [...data.tags, tagId]);
    };

    return (
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
                            <Label>Tags</Label>
                            <div className="mt-2 flex flex-wrap gap-2">
                                {tags.map((tag) => (
                                    <Button
                                        key={tag.id}
                                        type="button"
                                        variant={data.tags.includes(tag.id) ? 'default' : 'outline'}
                                        size="sm"
                                        onClick={() => handleTagChange(tag.id)}
                                    >
                                        {tag.name}
                                    </Button>
                                ))}
                            </div>
                            {errors.tags && <p className="mt-1 text-sm text-red-500">{errors.tags}</p>}
                        </div>

                        <Button type="submit" disabled={processing} className="w-full">
                            {processing ? 'Publishing...' : 'Publish Blog'}
                        </Button>
                    </form>
                </CardContent>
            </Card>
        </div>
    );
}
