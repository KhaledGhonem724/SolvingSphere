import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { ExternalLink, Filter, Plus, RotateCcw } from 'lucide-react';
import { useState } from 'react';

// Mock data for submissions
const mockSubmissions = [
    {
        id: 1,
        code: 'def two_sum(nums, target):\n    num_map = {}\n    for i, num in enumerate(nums):\n        complement = target - num\n        if complement in num_map:\n            return [num_map[complement], i]\n        num_map[num] = i\n    return []',
        language: 'python',
        result: 'solved',
        oj_response: 'Accepted',
        original_link: 'https://leetcode.com/submissions/detail/123456789/',
        created_at: new Date(2025, 5, 15).toISOString(),
        problem: {
            id: 1,
            problem_handle: 'LC-001',
            title: 'Two Sum',
            website: 'HackerEarth',
        },
        owner: {
            user_handle: 'khalid',
            name: 'khalid awad',
        },
    },
    {
        id: 2,
        code: '#include <iostream>\nvector<int> twoSum(vector<int>& nums, int target) {\n    unordered_map<int, int> numMap;\n    for (int i = 0; i < nums.size(); i++) {\n        int complement = target - nums[i];\n        if (numMap.find(complement) != numMap.end()) {\n            return {numMap[complement], i};\n        }\n        numMap[nums[i]] = i;\n    }\n    return {};\n}',
        language: 'cpp',
        result: 'attempted',
        oj_response: 'Wrong Answer',
        original_link: 'https://hackerearth.com/submissions/987654321/',
        created_at: new Date(2025, 4, 10).toISOString(),
        problem: {
            id: 2,
            problem_handle: 'HE-001',
            title: 'Array Manipulation Challenge',
            website: 'HackerEarth',
        },
        owner: {
            user_handle: 'mostafa23',
            name: 'mostafa khalifa',
        },
    },
    {
        id: 3,
        code: 'public int[] twoSum(int[] nums, int target) {\n    Map<Integer, Integer> numMap = new HashMap<>();\n    for (int i = 0; i < nums.length; i++) {\n        int complement = target - nums[i];\n        if (numMap.containsKey(complement)) {\n            return new int[] {numMap.get(complement), i};\n        }\n        numMap.put(nums[i], i);\n    }\n    return new int[]{};\n}',
        language: 'java',
        result: 'compilation error',
        oj_response: 'Compilation Error',
        original_link: null,
        created_at: new Date(2025, 3, 5).toISOString(),
        problem: {
            id: 3,
            problem_handle: 'MW-001',
            title: 'Graph Traversal Complex',
            website: 'McClure-Witting',
        },
        owner: {
            user_handle: 'code_wizard',
            name: 'Alice Johnson',
        },
    },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Submissions',
        href: '/submissions',
    },
];

export default function SubmissionsIndex() {
    const [filters, setFilters] = useState({
        problem_title: '',
        user_handle: '',
        result: 'all',
        language: 'all',
        my_submissions_only: false,
    });

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
                        <Button>
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
                                    value={filters.user_handle}
                                    onChange={(e) => setFilters((prev) => ({ ...prev, user_handle: e.target.value }))}
                                />
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium">Problem Title</label>
                                <Input
                                    placeholder="Search by problem..."
                                    value={filters.problem_title}
                                    onChange={(e) => setFilters((prev) => ({ ...prev, problem_title: e.target.value }))}
                                />
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium">Result</label>
                                <Select value={filters.result} onValueChange={(value) => setFilters((prev) => ({ ...prev, result: value }))}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="All" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All</SelectItem>
                                        <SelectItem value="solved">Solved</SelectItem>
                                        <SelectItem value="attempted">Attempted</SelectItem>
                                        <SelectItem value="wrong answer">Wrong Answer</SelectItem>
                                        <SelectItem value="time limit exceeded">Time Limit Exceeded</SelectItem>
                                        <SelectItem value="compilation error">Compilation Error</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium">Language</label>
                                <Select value={filters.language} onValueChange={(value) => setFilters((prev) => ({ ...prev, language: value }))}>
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
                                checked={filters.my_submissions_only}
                                onCheckedChange={(checked) => setFilters((prev) => ({ ...prev, my_submissions_only: checked as boolean }))}
                            />
                            <label htmlFor="my-submissions" className="text-muted-foreground cursor-pointer text-sm">
                                My submissions only
                            </label>
                        </div>

                        <div className="flex gap-2">
                            <Button>
                                <Filter className="mr-2 h-4 w-4" />
                                Filter
                            </Button>
                            <Button variant="outline">
                                <RotateCcw className="mr-2 h-4 w-4" />
                                Reset
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                {/* Submissions List */}
                <div className="space-y-4">
                    {mockSubmissions.map((submission) => (
                        <Card key={submission.id} className="transition-shadow hover:shadow-md">
                            <CardContent className="p-4">
                                <div className="flex items-center justify-between">
                                    <div className="flex-1">
                                        <div className="mb-2 flex items-center gap-3">
                                            <a
                                                href={`/submissions/${submission.id}`}
                                                className="text-lg font-medium transition-colors hover:text-blue-600"
                                            >
                                                {submission.problem.title}
                                            </a>
                                            <span className={`rounded-full px-2 py-1 text-xs font-semibold ${getResultColor(submission.result)}`}>
                                                {submission.result.charAt(0).toUpperCase() + submission.result.slice(1)}
                                            </span>
                                        </div>
                                        <p className="text-muted-foreground mb-2 text-sm">{submission.problem.problem_handle}</p>
                                        <div className="text-muted-foreground mb-2 flex items-center gap-4 text-sm">
                                            <span>
                                                Language: <strong>{submission.language}</strong>
                                            </span>
                                            <span>•</span>
                                            <span>
                                                By: <strong>{submission.owner.user_handle}</strong>
                                            </span>
                                            <span>•</span>
                                            <span>{formatTimeAgo(submission.created_at)}</span>
                                        </div>
                                        <div className="mt-2 flex items-center gap-2">
                                            <Badge variant="secondary">{submission.problem.website}</Badge>
                                            {submission.original_link && (
                                                <a
                                                    href={submission.original_link}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800"
                                                >
                                                    View Original <ExternalLink className="h-3 w-3" />
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
