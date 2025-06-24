import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/core';
import { Head } from '@inertiajs/react';
import { ExternalLink, Filter, Plus, RotateCcw } from 'lucide-react';
import { useState } from 'react';

interface Submission {
    id: number;
    code: string;
    language: string;
    result: string;
    oj_response: string;
    original_link: string | null;
    created_at: string;
    owner_id: string;
    problem_id: string;
    problem?: {
        problem_handle: string;
        title: string;
        website: string;
        link: string;
    } | null;
    owner?: {
        user_handle: string;
        name: string;
        email: string;
    } | null;
}

interface SubmissionsIndexProps {
    submissions: Submission[];
    filters?: {
        problem_title?: string;
        user_handle?: string;
        result?: string;
        language?: string;
        my_submissions_only?: boolean;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Submissions',
        href: '/submissions',
    },
];

export default function SubmissionsIndex({ submissions = [], filters = {} }: SubmissionsIndexProps) {
    const [currentFilters, setCurrentFilters] = useState({
        problem_title: filters.problem_title || '',
        user_handle: filters.user_handle || '',
        result: filters.result || 'all',
        language: filters.language || 'all',
        my_submissions_only: filters.my_submissions_only || false,
    });

    const applyFilters = () => {
        const filterParams: Record<string, string | boolean> = {};

        if (currentFilters.problem_title) filterParams.problem_title = currentFilters.problem_title;
        if (currentFilters.user_handle) filterParams.user_handle = currentFilters.user_handle;
        if (currentFilters.result !== 'all') filterParams.result = currentFilters.result;
        if (currentFilters.language !== 'all') filterParams.language = currentFilters.language;
        if (currentFilters.my_submissions_only) filterParams.my_submissions_only = currentFilters.my_submissions_only;

        router.get('/submissions', filterParams, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const resetFilters = () => {
        setCurrentFilters({
            problem_title: '',
            user_handle: '',
            result: 'all',
            language: 'all',
            my_submissions_only: false,
        });

        router.get(
            '/submissions',
            {},
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    const getResultColor = (result: string) => {
        switch (result.toLowerCase()) {
            case 'solved':
                return 'bg-green-100 text-green-700';
            case 'attempted':
                return 'bg-blue-100 text-blue-700';
            default:
                return 'bg-gray-100 text-gray-700';
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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Submissions" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">All Submissions</h1>
                    <div className="flex items-center gap-2">
                        <span className="text-muted-foreground text-sm">Submit your solution!!</span>
                        <Button onClick={() => router.get('/submissions/create')}>
                            <Plus className="mr-2 h-4 w-4" />
                            Submit Solution
                        </Button>
                    </div>
                </div>

                {/* Filters */}
                <Card>
                    <CardHeader>
                        <CardTitle className="text-lg">Filters</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                            <div>
                                <label className="mb-2 block text-sm font-medium">User Handle</label>
                                <Input
                                    placeholder="Search by user..."
                                    value={currentFilters.user_handle}
                                    onChange={(e) => setCurrentFilters((prev) => ({ ...prev, user_handle: e.target.value }))}
                                />
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium">Problem Title</label>
                                <Input
                                    placeholder="Search by problem..."
                                    value={currentFilters.problem_title}
                                    onChange={(e) => setCurrentFilters((prev) => ({ ...prev, problem_title: e.target.value }))}
                                />
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium">Result</label>
                                <Select
                                    value={currentFilters.result}
                                    onValueChange={(value) => setCurrentFilters((prev) => ({ ...prev, result: value }))}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="All" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All</SelectItem>
                                        <SelectItem value="solved">Solved</SelectItem>
                                        <SelectItem value="attempted">Attempted</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium">Language</label>
                                <Select
                                    value={currentFilters.language}
                                    onValueChange={(value) => setCurrentFilters((prev) => ({ ...prev, language: value }))}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="All" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All</SelectItem>
                                        <SelectItem value="cpp">C++</SelectItem>
                                        <SelectItem value="java">Java</SelectItem>
                                        <SelectItem value="python">Python</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <div className="flex items-center space-x-2">
                            <Checkbox
                                id="my-submissions"
                                checked={currentFilters.my_submissions_only}
                                onCheckedChange={(checked) => setCurrentFilters((prev) => ({ ...prev, my_submissions_only: checked as boolean }))}
                            />
                            <label htmlFor="my-submissions" className="text-muted-foreground cursor-pointer text-sm">
                                My submissions only
                            </label>
                        </div>

                        <div className="flex gap-2">
                            <Button onClick={applyFilters}>
                                <Filter className="mr-2 h-4 w-4" />
                                Apply Filters
                            </Button>
                            <Button variant="outline" onClick={resetFilters}>
                                <RotateCcw className="mr-2 h-4 w-4" />
                                Reset
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                {/* Submissions List */}
                <div className="space-y-4">
                    {submissions.map((submission) => (
                        <Card key={submission.problem?.problem_handle} className="transition-shadow hover:shadow-md">
                            <CardContent className="p-4">
                                <div className="flex items-center justify-between">
                                    <div className="flex-1">
                                        <div className="mb-2 flex items-center gap-3">
                                            <a
                                                href={`/submissions/${submission.id}`}
                                                className="text-lg font-medium transition-colors hover:text-blue-600"
                                            >
                                                {submission.problem?.problem_handle || 'Problem Not Found'}
                                            </a>
                                            <span className={`rounded-full px-2 py-1 text-xs font-semibold ${getResultColor(submission.result)}`}>
                                                {submission.result.charAt(0).toUpperCase() + submission.result.slice(1)}
                                            </span>
                                        </div>

                                        <div className="text-muted-foreground mb-2 flex items-center gap-4 text-sm">
                                            <span>
                                                Language: <strong>{submission.language}</strong>
                                            </span>
                                            <span>•</span>
                                            <span>
                                                By: <strong>{submission.owner?.name || 'User Not Found'}</strong>
                                            </span>
                                            <span>•</span>
                                            <span>{formatTimeAgo(submission.created_at)}</span>
                                        </div>
                                        <div className="mt-2 flex items-center gap-2">
                                            <Badge variant="secondary">{submission.problem?.website || 'Unknown OJ'}</Badge>
                                            {submission.original_link && (
                                                <a
                                                    href={submission.original_link}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="flex items-center text-xs text-blue-600 hover:underline"
                                                >
                                                    <ExternalLink className="mr-1 h-3 w-3" />
                                                    Original Submission
                                                </a>
                                            )}
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
