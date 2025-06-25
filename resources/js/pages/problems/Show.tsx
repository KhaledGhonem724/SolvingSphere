import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { router } from '@inertiajs/core';
import { 
    ExternalLink, 
    Clock, 
    HardDrive, 
    Code, 
    CheckCircle, 
    XCircle, 
    AlertCircle,
    Play,
    Eye
} from 'lucide-react';

interface Problem {
    problem_handle: string;
    title: string;
    website: string;
    link: string;
    timelimit: string;
    memorylimit: string;
    statement: string;
    testcases: string;
    notes?: string;
    created_at: string;
    tags: Array<{ id: number; name: string }>;
    submissions: Array<{
        id: number;
        result: string;
        language: string;
        created_at: string;
        owner: {
            user_handle: string;
            name: string;
        };
    }>;
}

interface UserSubmission {
    id: number;
    result: string;
    language: string;
    created_at: string;
    owner: {
        user_handle: string;
        name: string;
    };
}

interface ShowProblemProps {
    problem: Problem;
    userSubmission?: UserSubmission | null;
}

export default function ShowProblem({ problem, userSubmission }: ShowProblemProps) {
    const getStatusIcon = (result: string) => {
        switch (result.toLowerCase()) {
            case 'solved':
                return <CheckCircle className="h-5 w-5 text-green-600" />;
            case 'attempted':
                return <AlertCircle className="h-5 w-5 text-yellow-600" />;
            default:
                return <XCircle className="h-5 w-5 text-red-600" />;
        }
    };

    const getStatusColor = (result: string) => {
        switch (result.toLowerCase()) {
            case 'solved':
                return 'bg-green-100 text-green-800';
            case 'attempted':
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-red-100 text-red-800';
        }
    };

    const formatTimeAgo = (dateString: string) => {
        const date = new Date(dateString);
        const now = new Date();
        const diffInDays = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60 * 24));
        
        if (diffInDays === 0) return 'Today';
        if (diffInDays === 1) return 'Yesterday';
        return `${diffInDays} days ago`;
    };

    const parseTestCases = (testcases: string) => {
        try {
            return JSON.parse(testcases);
        } catch {
            return testcases;
        }
    };

    const breadcrumbs = [
        {
            title: 'Problems',
            href: '/problems',
        },
        {
            title: problem.title,
            href: `/problems/${problem.problem_handle}`,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${problem.title} - Problem Details`} />
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
                {/* Problem Header */}
                <div className="flex items-start justify-between">
                    <div className="flex-1">
                        <div className="flex items-center gap-3 mb-2">
                            <h1 className="text-3xl font-bold">{problem.title}</h1>
                            {userSubmission && (
                                <div className="flex items-center gap-2">
                                    {getStatusIcon(userSubmission.result)}
                                    <span className={`rounded-full px-3 py-1 text-sm font-semibold ${getStatusColor(userSubmission.result)}`}>
                                        {userSubmission.result.charAt(0).toUpperCase() + userSubmission.result.slice(1)}
                                    </span>
                                </div>
                            )}
                        </div>
                        <p className="text-lg text-muted-foreground mb-2">{problem.problem_handle}</p>
                        
                        {/* Original Problem Link */}
                        <div className="mb-4">
                            <a
                                href={problem.link}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 hover:bg-blue-100 transition-colors text-sm font-medium"
                            >
                                <Badge variant="outline" className="border-blue-300 text-blue-700">
                                    {problem.website}
                                </Badge>
                                View Original Problem <ExternalLink className="h-4 w-4" />
                            </a>
                        </div>
                        
                        {/* Tags */}
                        <div className="flex flex-wrap gap-2 mb-4">
                            {problem.tags.map((tag) => (
                                <Badge key={tag.id} variant="secondary">
                                    {tag.name}
                                </Badge>
                            ))}
                        </div>
                    </div>
                    
                    <div className="flex items-center gap-3">
                        <Button
                            onClick={() => router.get('/submissions/create')}
                            className="bg-green-600 hover:bg-green-700"
                        >
                            <Play className="mr-2 h-4 w-4" />
                            Submit Solution
                        </Button>
                        <Button
                            variant="outline"
                            onClick={() => router.get('/submissions')}
                        >
                            <Eye className="mr-2 h-4 w-4" />
                            View Submissions
                        </Button>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Main Content */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Problem Statement */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Problem Statement</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div 
                                    className="prose prose-sm max-w-none whitespace-pre-wrap"
                                    dangerouslySetInnerHTML={{ __html: problem.statement }}
                                />
                            </CardContent>
                        </Card>

                        {/* Test Cases */}
                        {problem.testcases && (
                            <Card>
                                <CardHeader>
                                    <CardTitle>Sample Test Cases</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div className="space-y-4">
                                        {typeof parseTestCases(problem.testcases) === 'string' ? (
                                            <pre className="bg-gray-100 border border-gray-200 p-4 rounded-lg overflow-x-auto text-sm text-gray-800 font-mono">
                                                {parseTestCases(problem.testcases)}
                                            </pre>
                                        ) : (
                                            <div className="grid gap-4">
                                                {Array.isArray(parseTestCases(problem.testcases)) ? 
                                                    parseTestCases(problem.testcases).map((testcase: any, index: number) => (
                                                        <div key={index} className="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                            <h4 className="font-semibold mb-3 text-gray-900">Test Case {index + 1}</h4>
                                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <p className="font-medium text-sm mb-2 text-gray-700">Input:</p>
                                                                    <pre className="bg-white border border-gray-200 p-3 rounded text-sm text-gray-800 font-mono overflow-x-auto">
                                                                        {testcase.input || 'N/A'}
                                                                    </pre>
                                                                </div>
                                                                <div>
                                                                    <p className="font-medium text-sm mb-2 text-gray-700">Output:</p>
                                                                    <pre className="bg-white border border-gray-200 p-3 rounded text-sm text-gray-800 font-mono overflow-x-auto">
                                                                        {testcase.output || 'N/A'}
                                                                    </pre>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    )) : (
                                                        <pre className="bg-gray-100 border border-gray-200 p-4 rounded-lg overflow-x-auto text-sm text-gray-800 font-mono">
                                                            {JSON.stringify(parseTestCases(problem.testcases), null, 2)}
                                                        </pre>
                                                    )
                                                }
                                            </div>
                                        )}
                                    </div>
                                </CardContent>
                            </Card>
                        )}

                        {/* Notes */}
                        {problem.notes && (
                            <Card>
                                <CardHeader>
                                    <CardTitle>Notes</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div 
                                        className="prose prose-sm max-w-none whitespace-pre-wrap"
                                        dangerouslySetInnerHTML={{ __html: problem.notes }}
                                    />
                                </CardContent>
                            </Card>
                        )}
                    </div>

                    {/* Sidebar */}
                    <div className="space-y-6">
                        {/* Problem Info */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Problem Information</CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="space-y-3">
                                    <div className="flex items-center gap-3">
                                        <Clock className="h-4 w-4 text-muted-foreground" />
                                        <span className="text-sm">
                                            <strong>Time Limit:</strong> {problem.timelimit}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-3">
                                        <HardDrive className="h-4 w-4 text-muted-foreground" />
                                        <span className="text-sm">
                                            <strong>Memory Limit:</strong> {problem.memorylimit}
                                        </span>
                                    </div>
                                    <div className="flex items-center gap-3">
                                        <Code className="h-4 w-4 text-muted-foreground" />
                                        <span className="text-sm">
                                            <strong>Added:</strong> {formatTimeAgo(problem.created_at)}
                                        </span>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        {/* User Status */}
                        {userSubmission && (
                            <Card>
                                <CardHeader>
                                    <CardTitle>Your Status</CardTitle>
                                </CardHeader>
                                <CardContent className="space-y-3">
                                    <div className="flex items-center gap-3">
                                        {getStatusIcon(userSubmission.result)}
                                        <span className="font-medium">
                                            {userSubmission.result.charAt(0).toUpperCase() + userSubmission.result.slice(1)}
                                        </span>
                                    </div>
                                    <div className="text-sm text-muted-foreground">
                                        <p>Language: <strong>{userSubmission.language}</strong></p>
                                        <p>Last attempt: {formatTimeAgo(userSubmission.created_at)}</p>
                                    </div>
                                    <Button 
                                        size="sm" 
                                        variant="outline" 
                                        className="w-full"
                                        onClick={() => router.get(`/submissions/${userSubmission.id}`)}
                                    >
                                        View Submission
                                    </Button>
                                </CardContent>
                            </Card>
                        )}

                        {/* Recent Submissions */}
                        {problem.submissions && problem.submissions.length > 0 && (
                            <Card>
                                <CardHeader>
                                    <CardTitle>Recent Submissions</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div className="space-y-3">
                                        {problem.submissions.slice(0, 5).map((submission) => (
                                            <div key={submission.id} className="flex items-center justify-between p-2 rounded-lg bg-gray-50">
                                                <div>
                                                    <p className="text-sm font-medium">{submission.owner.user_handle}</p>
                                                    <p className="text-xs text-muted-foreground">{submission.language}</p>
                                                </div>
                                                <div className="text-right">
                                                    <span className={`px-2 py-1 rounded-full text-xs ${getStatusColor(submission.result)}`}>
                                                        {submission.result}
                                                    </span>
                                                    <p className="text-xs text-muted-foreground mt-1">
                                                        {formatTimeAgo(submission.created_at)}
                                                    </p>
                                                </div>
                                            </div>
                                        ))}
                                        {problem.submissions.length > 5 && (
                                            <Button 
                                                size="sm" 
                                                variant="outline" 
                                                className="w-full"
                                                onClick={() => router.get('/submissions')}
                                            >
                                                View All Submissions ({problem.submissions.length})
                                            </Button>
                                        )}
                                    </div>
                                </CardContent>
                            </Card>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
} 