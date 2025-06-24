import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Alert, AlertDescription } from '@/components/ui/alert';
import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';
import { AlertCircle, Code, Send } from 'lucide-react';

interface CreateSubmissionProps {
    errors?: {
        problem_handle?: string;
        code?: string;
        language?: string;
        error?: string;
    };
}

export default function CreateSubmission({ errors = {} }: CreateSubmissionProps) {
    const { data, setData, post, processing } = useForm({
        problem_handle: '',
        code: '',
        language: 'python',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/submissions/create');
    };

    const breadcrumbs = [
        {
            title: 'Submissions',
            href: '/submissions',
        },
        {
            title: 'Submit Solution',
            href: '/submissions/create',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Submit Solution" />
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
                <div>
                    <h1 className="text-2xl font-bold">Submit Solution</h1>
                    <p className="text-muted-foreground mt-2">
                        Submit your solution for a problem in our database.
                    </p>
                </div>

                {/* Error Alert */}
                {(errors.problem_handle || errors.code || errors.language || errors.error) && (
                    <Alert variant="destructive">
                        <AlertCircle className="h-4 w-4" />
                        <AlertDescription>
                            {errors.problem_handle || errors.code || errors.language || errors.error}
                        </AlertDescription>
                    </Alert>
                )}

                {/* Instructions */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Code className="h-5 w-5" />
                            Instructions
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div>
                            <h3 className="font-semibold mb-2">How to submit a solution:</h3>
                            <ol className="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                <li>Enter the problem handle (e.g., HE-001, LC-001)</li>
                                <li>Select your programming language</li>
                                <li>Paste your code solution</li>
                                <li>Click "Submit Solution" to test your code</li>
                            </ol>
                        </div>
                        
                        <div className="bg-blue-50 p-4 rounded-lg">
                            <h4 className="font-semibold text-blue-900 mb-2">Supported Languages:</h4>
                            <ul className="text-sm text-blue-800 space-y-1">
                                <li>• Python</li>
                                <li>• C++</li>
                                <li>• Java</li>
                            </ul>
                        </div>
                    </CardContent>
                </Card>

                {/* Submission Form */}
                <Card>
                    <CardHeader>
                        <CardTitle>Submit Your Solution</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label htmlFor="problem_handle" className="block text-sm font-medium mb-2">
                                        Problem Handle
                                    </label>
                                    <Input
                                        id="problem_handle"
                                        type="text"
                                        placeholder="e.g., HE-001, LC-001"
                                        value={data.problem_handle}
                                        onChange={(e) => setData('problem_handle', e.target.value)}
                                        required
                                    />
                                </div>
                                <div>
                                    <label htmlFor="language" className="block text-sm font-medium mb-2">
                                        Programming Language
                                    </label>
                                    <Select value={data.language} onValueChange={(value) => setData('language', value)}>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select language" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="python">Python</SelectItem>
                                            <SelectItem value="cpp">C++</SelectItem>
                                            <SelectItem value="java">Java</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <div>
                                <label htmlFor="code" className="block text-sm font-medium mb-2">
                                    Solution Code
                                </label>
                                <Textarea
                                    id="code"
                                    placeholder="Paste your solution code here..."
                                    value={data.code}
                                    onChange={(e) => setData('code', e.target.value)}
                                    className="min-h-[300px] font-mono text-sm"
                                    required
                                />
                            </div>

                            <div className="flex gap-3">
                                <Button 
                                    type="submit" 
                                    disabled={processing || !data.problem_handle || !data.code}
                                    className="flex-1"
                                >
                                    <Send className="mr-2 h-4 w-4" />
                                    {processing ? 'Submitting Solution...' : 'Submit Solution'}
                                </Button>
                                <Button 
                                    type="button" 
                                    variant="outline" 
                                    onClick={() => window.history.back()}
                                >
                                    Cancel
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>

                {/* Preview Section */}
                {data.problem_handle && data.code && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Submission Preview</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-3">
                                <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p className="font-medium">Problem: {data.problem_handle}</p>
                                        <p className="text-sm text-muted-foreground">Language: {data.language}</p>
                                        <p className="text-sm text-muted-foreground">Code length: {data.code.length} characters</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppLayout>
    );
} 