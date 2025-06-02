import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';

interface Blog {
    id: number;
    title: string;
    content: string;
    owner: {
        name: string;
    };
    tags: Array<{ id: number; name: string }>;
    created_at: string;
}

interface BlogIndexProps {
    blogs: {
        data: Blog[];
        current_page: number;
        last_page: number;
    };
    allTags: Array<{ id: number; name: string }>;
}

export default function BlogIndex({ blogs, allTags }: BlogIndexProps) {
    const [filters, setFilters] = useState({
        title: '',
        blog_type: '',
        owner_id: '',
        tags: [] as number[],
        match_all_tags: false,
    });

    const handleFilterChange = (key: string, value: any) => {
        setFilters((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    const handleTagToggle = (tagId: number) => {
        setFilters((prev) => ({
            ...prev,
            tags: prev.tags.includes(tagId) ? prev.tags.filter((id) => id !== tagId) : [...prev.tags, tagId],
        }));
    };

    const applyFilters = () => {
        // Construct query parameters based on filters
        const queryParams = new URLSearchParams();

        if (filters.title) queryParams.append('title', filters.title);
        if (filters.blog_type && filters.blog_type !== 'all') {
            queryParams.append('blog_type', filters.blog_type);
        }
        if (filters.owner_id) queryParams.append('owner_id', filters.owner_id);
        if (filters.tags.length > 0) {
            filters.tags.forEach((tag) => queryParams.append('tags[]', tag.toString()));
        }
        if (filters.match_all_tags) {
            queryParams.append('match_all_tags', '1');
        }

        // Navigate to the filtered route
        window.location.href = `/blogs?${queryParams.toString()}`;
    };

    return (
        <AppSidebarLayout>
            <div className="container mx-auto px-4 py-8">
                <Head title="Blogs" />

                {/* Filters Section */}
                <div className="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <Input placeholder="Filter by Title" value={filters.title} onChange={(e) => handleFilterChange('title', e.target.value)} />
                    <Select value={filters.blog_type} onValueChange={(value) => handleFilterChange('blog_type', value)}>
                        <SelectTrigger>
                            <SelectValue placeholder="Blog Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Types</SelectItem>
                            <SelectItem value="discussion">Discussion</SelectItem>
                            <SelectItem value="question">Question</SelectItem>
                            <SelectItem value="explain">Explain</SelectItem>
                        </SelectContent>
                    </Select>
                    <Input placeholder="Owner ID" value={filters.owner_id} onChange={(e) => handleFilterChange('owner_id', e.target.value)} />
                </div>

                {/* Tags Filter */}
                <div className="mb-8">
                    <h3 className="mb-4 text-lg font-semibold">Filter by Tags</h3>
                    <div className="flex flex-wrap gap-4">
                        {allTags.map((tag) => (
                            <div key={tag.id} className="flex items-center space-x-2">
                                <Checkbox
                                    id={`tag-${tag.id}`}
                                    checked={filters.tags.includes(tag.id)}
                                    onCheckedChange={() => handleTagToggle(tag.id)}
                                />
                                <label
                                    htmlFor={`tag-${tag.id}`}
                                    className="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                >
                                    {tag.name}
                                </label>
                            </div>
                        ))}
                    </div>
                    <div className="mt-4 flex items-center space-x-2">
                        <Checkbox
                            id="match-all-tags"
                            checked={filters.match_all_tags}
                            onCheckedChange={(checked) => handleFilterChange('match_all_tags', checked)}
                        />
                        <label
                            htmlFor="match-all-tags"
                            className="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        >
                            Match all selected tags
                        </label>
                    </div>
                </div>

                {/* Filter Buttons */}
                <div className="mb-8 flex space-x-4">
                    <Button onClick={applyFilters}>Apply Filters</Button>
                    <Button
                        variant="secondary"
                        onClick={() =>
                            setFilters({
                                title: '',
                                blog_type: '',
                                owner_id: '',
                                tags: [],
                                match_all_tags: false,
                            })
                        }
                    >
                        Reset Filters
                    </Button>
                </div>

                <div className="mb-8 flex items-center justify-between">
                    <h1 className="text-3xl font-bold">Latest Blogs</h1>
                    <Link href="/blogs/create">
                        <Button>Create New Blog</Button>
                    </Link>
                </div>

                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {blogs.data.map((blog) => (
                        <Card key={blog.id} className="transition-shadow hover:shadow-lg">
                            <CardHeader>
                                <CardTitle>{blog.title}</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-muted-foreground line-clamp-3">{blog.content}</p>
                                <div className="mt-4 flex flex-wrap gap-2">
                                    {blog.tags.map((tag) => (
                                        <Badge key={tag.id} variant="secondary">
                                            {tag.name}
                                        </Badge>
                                    ))}
                                </div>
                            </CardContent>
                            <CardFooter className="flex items-center justify-between">
                                <div className="text-muted-foreground text-sm">By {blog.owner.name}</div>
                                <Link href={`/blogs/${blog.id}`}>
                                    <Button variant="outline" size="sm">
                                        Read More
                                    </Button>
                                </Link>
                            </CardFooter>
                        </Card>
                    ))}
                </div>

                {/* Pagination */}
                <div className="mt-8 flex justify-center space-x-2">
                    {blogs.current_page > 1 && (
                        <Link href={`/blogs?page=${blogs.current_page - 1}`}>
                            <Button variant="outline">Previous</Button>
                        </Link>
                    )}
                    {blogs.current_page < blogs.last_page && (
                        <Link href={`/blogs?page=${blogs.current_page + 1}`}>
                            <Button variant="outline">Next</Button>
                        </Link>
                    )}
                </div>
            </div>
        </AppSidebarLayout>
    );
}
