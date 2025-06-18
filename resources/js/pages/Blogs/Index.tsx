import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';

// Utility function to format time ago
function formatTimeAgo(dateString: string): string {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

    const units = [
        { name: 'year', seconds: 31536000 },
        { name: 'month', seconds: 2592000 },
        { name: 'week', seconds: 604800 },
        { name: 'day', seconds: 86400 },
        { name: 'hour', seconds: 3600 },
        { name: 'minute', seconds: 60 },
        { name: 'second', seconds: 1 },
    ];

    for (const unit of units) {
        const value = Math.floor(diffInSeconds / unit.seconds);
        if (value >= 1) {
            return `${value} ${unit.name}${value > 1 ? 's' : ''} ago`;
        }
    }

    return 'just now';
}

interface Blog {
    id: number;
    title: string;
    content: string;
    blog_type: string;
    owner: {
        user_handle: string;
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
    filters: {
        title: string;
        blog_type: string;
        owner_id: string;
        tags: number[];
        match_all_tags: boolean;
        my_blogs_only: boolean;
    };
}

export default function BlogIndex({ blogs, allTags, filters: initialFilters }: BlogIndexProps) {
    const [filters, setFilters] = useState({
        title: initialFilters.title || '',
        blog_type: initialFilters.blog_type || 'all',
        user_handle: initialFilters.owner_id || '',
        tags: initialFilters.tags || [],
        match_all_tags: initialFilters.match_all_tags || false,
        my_blogs_only: initialFilters.my_blogs_only || false,
    });

    const handleFilterChange = (key: 'title' | 'blog_type' | 'user_handle' | 'match_all_tags' | 'my_blogs_only', value: string | boolean) => {
        setFilters((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    const handleTagToggle = (tagId: number) => {
        setFilters((prev) => {
            const currentTags = [...prev.tags];
            const tagIndex = currentTags.indexOf(tagId);

            if (tagIndex > -1) {
                // Tag is already selected, remove it
                currentTags.splice(tagIndex, 1);
            } else {
                // Tag is not selected, add it
                currentTags.push(tagId);
            }

            return {
                ...prev,
                tags: currentTags,
            };
        });
    };

    const applyFilters = () => {
        // Construct query parameters based on filters
        const queryParams = new URLSearchParams();

        if (filters.title) queryParams.append('title', filters.title);
        if (filters.blog_type && filters.blog_type !== 'all') {
            queryParams.append('blog_type', filters.blog_type);
        }
        if (filters.user_handle) queryParams.append('owner_id', filters.user_handle);

        // Correctly append tags
        if (filters.tags.length > 0) {
            filters.tags.forEach((tag) => queryParams.append('tags[]', tag.toString()));
        }

        if (filters.match_all_tags) {
            queryParams.append('match_all_tags', '1');
        }

        // Add my blogs only filter
        if (filters.my_blogs_only) {
            queryParams.append('my_blogs_only', '1');
        }

        // Navigate to the filtered route
        window.location.href = `/blogs?${queryParams.toString()}`;
    };

    const resetFilters = () => {
        setFilters({
            title: '',
            blog_type: 'all',
            user_handle: '',
            tags: [],
            match_all_tags: false,
            my_blogs_only: false,
        });
        window.location.href = '/blogs';
    };

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Blogs',
            href: '/blogs',
        },
    ];

    return (
        <AppSidebarLayout breadcrumbs={breadcrumbs}>
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
                    <Input
                        placeholder="Filter by User Handle"
                        value={filters.user_handle}
                        onChange={(e) => handleFilterChange('user_handle', e.target.value)}
                    />
                </div>

                {/* Tags Filter */}
                <div className="mb-8">
                    <h3 className="mb-4 text-lg font-semibold">Filter by Tags</h3>
                    <div className="flex flex-wrap gap-2">
                        {allTags.map((tag) => {
                            const isSelected = filters.tags.includes(tag.id);
                            return (
                                <Button
                                    key={tag.id}
                                    variant={isSelected ? 'default' : 'outline'}
                                    className={`rounded-full px-4 py-1 text-sm ${isSelected ? 'bg-green-500 text-white' : ''}`}
                                    onClick={() => handleTagToggle(tag.id)}
                                >
                                    {tag.name}
                                </Button>
                            );
                        })}
                    </div>

                    {/* Match all tags checkbox remains the same */}
                    <div className="mt-4 flex items-center space-x-4">
                        <span className="text-sm font-medium">Match All Selected Tags</span>
                        <button
                            onClick={() => handleFilterChange('match_all_tags', !filters.match_all_tags)}
                            className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 ${
                                filters.match_all_tags ? 'bg-green-500' : 'bg-gray-300'
                            }`}
                        >
                            <span
                                className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 ${
                                    filters.match_all_tags ? 'translate-x-6' : 'translate-x-1'
                                }`}
                            />
                        </button>
                    </div>
                </div>

                {/* Filter Buttons */}
                <div className="mb-8 flex items-center space-x-4">
                    <Button onClick={applyFilters}>Apply Filters</Button>
                    <Button variant="secondary" onClick={resetFilters}>
                        Reset Filters
                    </Button>
                    <Button
                        variant={filters.my_blogs_only ? 'default' : 'outline'}
                        className={`rounded-full px-4 py-1 text-sm ${filters.my_blogs_only ? 'bg-green-500 text-white' : ''}`}
                        onClick={() => handleFilterChange('my_blogs_only', !filters.my_blogs_only)}
                    >
                        Show My Blogs Only
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
                                <div className="flex items-center justify-between">
                                    <CardTitle>{blog.title}</CardTitle>
                                    <Badge variant="outline" className="capitalize">
                                        {blog.blog_type}
                                    </Badge>
                                </div>
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
                                <div className="text-muted-foreground text-sm">
                                    <div>By {blog.owner.user_handle}</div>
                                    <div>{formatTimeAgo(blog.created_at)}</div>
                                </div>
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
