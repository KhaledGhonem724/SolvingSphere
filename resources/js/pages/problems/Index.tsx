import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { useState } from 'react';

interface Problem {
    id: number;
    problem_handle: string;
    title: string;
    website: string;
    link: string;
    timelimit: string;
    memorylimit: string;
    created_at: string;
    state?: string;
    tags: Array<{ id: number; name: string }>;
}

interface ProblemsIndexProps {
    allTags: Array<{ id: number; name: string }>;
}

export default function ProblemsIndex({ allTags = [] }: ProblemsIndexProps) {
    // Static problems
    const problems = {
        data: [
            {
                id: 1,
                problem_handle: 'HE-001',
                title: 'Make an array',
                website: 'HackerEarth',
                link: 'https://www.hackerearth.com/problem/array-manipulation',
                timelimit: '2 Sec',
                memorylimit: '256 MB',
                created_at: new Date(2025, 5, 1).toISOString(),
                state: 'solved',
                tags: [
                    { id: 1, name: 'Array' },
                    { id: 2, name: 'Dynamic Programming' },
                ],
            },
            {
                id: 2,
                problem_handle: 'LC-001',
                title: 'Two Sum',
                website: 'HackerEarth',
                link: 'https://leetcode.com/problems/two-sum',
                timelimit: '1 Sec',
                memorylimit: '128 MB',
                created_at: new Date(2025, 5, 9).toISOString(),
                state: 'attempted',
                tags: [
                    { id: 3, name: 'Hash Table' },
                    { id: 4, name: 'Easy' },
                ],
            },
            {
                id: 3,
                problem_handle: 'MW-001',
                title: 'Graph Traversal Complex',
                website: 'McClure-Witting',
                link: 'https://mcclure-witting.com/problem/graph-traversal',
                timelimit: '3 Sec',
                memorylimit: '512 MB',
                created_at: new Date(2023, 3, 5).toISOString(),
                state: 'todo',
                tags: [
                    { id: 5, name: 'Graph' },
                    { id: 6, name: 'Hard' },
                    { id: 7, name: 'BFS' },
                ],
            },
        ],
        current_page: 1,
        last_page: 1,
    };

    const [filters, setFilters] = useState({
        title: '',
        website: 'all',
        state: 'all',
        tags: [],
        match_all_tags: false,
    });

    const handleFilterChange = (key: string, value: string | boolean) => {
        setFilters((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    const getStatusColor = (state: string) => {
        switch (state) {
            case 'solved':
                return 'bg-green-100 text-green-800';
            case 'attempted':
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-gray-100 text-gray-700';
        }
    };

    const getStatusText = (state: string) => {
        switch (state) {
            case 'solved':
                return 'Solved';
            case 'attempted':
                return 'Attempted';
            default:
                return 'Todo';
        }
    };

    const formatTimeAgo = (dateString: string) => {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

        if (diffInSeconds < 60) return `${diffInSeconds} seconds ago`;
        const diffInMinutes = Math.floor(diffInSeconds / 60);
        if (diffInMinutes < 60) return `${diffInMinutes} minutes ago`;
        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) return `${diffInHours} hours ago`;
        const diffInDays = Math.floor(diffInHours / 24);
        return `${diffInDays} days ago`;
    };

    const handleTagToggle = (tagId: number) => {
        setFilters((prev) => {
            const currentTags = [...prev.tags];
            const tagIndex = currentTags.indexOf(tagId);

            if (tagIndex > -1) {
                currentTags.splice(tagIndex, 1);
            } else {
                currentTags.push(tagId);
            }

            return {
                ...prev,
                tags: currentTags,
            };
        });
    };

    const breadcrumbs = [
        {
            title: 'Problems',
            href: '/problems',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Problems" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">All Problems</h1>
                    <div className="flex items-center gap-2">
                        <span className="text-muted-foreground text-sm">Can't find your problem?</span>
                        <Button>
                            <Plus className="mr-2 h-4 w-4" />
                            Scrape New Problem
                        </Button>
                    </div>
                </div>

                {/* Filters */}
                <Card>
                    <CardHeader>
                        <CardTitle className="text-lg">Filters</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label className="mb-2 block text-sm font-medium">Problem Name</label>
                                <Input
                                    placeholder="Search problems..."
                                    value={filters.title}
                                    onChange={(e) => handleFilterChange('title', e.target.value)}
                                />
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium">Online Judge</label>
                                <Select value={filters.website} onValueChange={(value) => handleFilterChange('website', value)}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="All" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All</SelectItem>
                                        <SelectItem value="HackerEarth">HackerEarth</SelectItem>
                                        <SelectItem value="LeetCode">LeetCode</SelectItem>
                                        <SelectItem value="McClure-Witting">McClure-Witting</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium">Problem State</label>
                                <Select value={filters.state} onValueChange={(value) => handleFilterChange('state', value)}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="All" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All</SelectItem>
                                        <SelectItem value="solved">Solved</SelectItem>
                                        <SelectItem value="attempted">Attempted</SelectItem>
                                        <SelectItem value="todo">Todo</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        {/* Tags Filter */}
                        <div>
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
                    </CardContent>
                </Card>

                {/* Problems List */}
                <div className="space-y-4">
                    {problems.data.map((problem) => (
                        <Card key={problem.id} className="transition-shadow hover:shadow-md">
                            <CardContent className="p-4">
                                <div className="flex items-start justify-between">
                                    <div className="flex-1">
                                        <div className="mb-2 flex items-center gap-3">
                                            <a
                                                href={problem.link}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="text-lg font-medium transition-colors hover:text-blue-600"
                                            >
                                                {problem.title}
                                            </a>
                                            <span
                                                className={`rounded-full px-2 py-1 text-xs font-semibold ${getStatusColor(problem.state || 'todo')}`}
                                            >
                                                {getStatusText(problem.state || 'todo')}
                                            </span>
                                        </div>
                                        <p className="text-muted-foreground mb-2 text-sm">{problem.problem_handle}</p>
                                        <div className="text-muted-foreground mb-2 flex items-center gap-4 text-sm">
                                            <span>
                                                Online Judge: <strong>{problem.website}</strong>
                                            </span>
                                            <span>•</span>
                                            <span>{formatTimeAgo(problem.created_at)}</span>
                                        </div>
                                        <div className="flex flex-wrap gap-2">
                                            {problem.tags.map((tag) => (
                                                <Badge key={tag.id} variant="secondary">
                                                    {tag.name}
                                                </Badge>
                                            ))}
                                        </div>
                                    </div>
                                    <div className="ml-4 text-right">
                                        <div className="text-muted-foreground text-sm">
                                            <strong>Time Limit:</strong> {problem.timelimit}
                                        </div>
                                        <div className="text-muted-foreground text-sm">
                                            <strong>Memory Limit:</strong> {problem.memorylimit}
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
