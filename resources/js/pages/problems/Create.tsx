import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Alert, AlertDescription } from '@/components/ui/alert';
import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';
import { AlertCircle, ExternalLink, Plus } from 'lucide-react';
import { useState } from 'react';

interface CreateProblemProps {
    errors?: {
        'problem-url'?: string;
        error?: string;
    };
}

export default function CreateProblem({ errors = {} }: CreateProblemProps) {
    const { data, setData, post, processing } = useForm({
        'problem-url': '',
    });

    const [isValidUrl, setIsValidUrl] = useState(false);

    const handleUrlChange = (url: string) => {
        setData('problem-url', url);
        
        // Basic URL validation for HackerEarth
        const isValid = url.includes('hackerearth.com') && url.includes('/problem/');
        setIsValidUrl(isValid);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/problems/create');
    };

    const breadcrumbs = [
        {
            title: 'Problems',
            href: '/problems',
        },
        {
            title: 'Scrape New Problem',
            href: '/problems/create',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Scrape New Problem" />
            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
                <div>
                    <h1 className="text-2xl font-bold">Scrape New Problem</h1>
                    <p className="text-muted-foreground mt-2">
                        Add a new problem to our database by providing the problem URL from HackerEarth.
                    </p>
                </div>

                {/* Error Alert */}
                {(errors['problem-url'] || errors.error) && (
                    <Alert variant="destructive">
                        <AlertCircle className="h-4 w-4" />
                        <AlertDescription>
                            {errors['problem-url'] || errors.error}
                        </AlertDescription>
                    </Alert>
                )}

                {/* Instructions */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <ExternalLink className="h-5 w-5" />
                            Instructions
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div>
                            <h3 className="font-semibold mb-2">How to scrape a problem:</h3>
                            <ol className="list-decimal list-inside space-y-2 text-sm text-muted-foreground">
                                <li>Go to HackerEarth and find the problem you want to add</li>
                                <li>Copy the problem URL (e.g., https://www.hackerearth.com/problem/algorithm/problem-name/)</li>
                                <li>Paste the URL in the field below</li>
                                <li>Click "Scrape Problem" to add it to our database</li>
                            </ol>
                        </div>
                        
                        <div className="bg-blue-50 p-4 rounded-lg">
                            <h4 className="font-semibold text-blue-900 mb-2">Supported Platforms:</h4>
                            <ul className="text-sm text-blue-800 space-y-1">
                                <li>• HackerEarth (Primary support)</li>
                                <li>• More platforms coming soon...</li>
                            </ul>
                        </div>
                    </CardContent>
                </Card>

                {/* Scrape Form */}
                <Card>
                    <CardHeader>
                        <CardTitle>Add New Problem</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div>
                                <label htmlFor="problem-url" className="block text-sm font-medium mb-2">
                                    Problem URL
                                </label>
                                <Input
                                    id="problem-url"
                                    type="url"
                                    placeholder="https://www.hackerearth.com/problem/algorithm/..."
                                    value={data['problem-url']}
                                    onChange={(e) => handleUrlChange(e.target.value)}
                                    className={`${
                                        data['problem-url'] && !isValidUrl 
                                            ? 'border-red-300 focus:border-red-500' 
                                            : data['problem-url'] && isValidUrl 
                                                ? 'border-green-300 focus:border-green-500' 
                                                : ''
                                    }`}
                                    required
                                />
                                {data['problem-url'] && !isValidUrl && (
                                    <p className="text-red-600 text-sm mt-1">
                                        Please enter a valid HackerEarth problem URL
                                    </p>
                                )}
                                {data['problem-url'] && isValidUrl && (
                                    <p className="text-green-600 text-sm mt-1">
                                        Valid URL format detected
                                    </p>
                                )}
                            </div>

                            <div className="flex gap-3">
                                <Button 
                                    type="submit" 
                                    disabled={processing || !isValidUrl || !data['problem-url']}
                                    className="flex-1"
                                >
                                    <Plus className="mr-2 h-4 w-4" />
                                    {processing ? 'Scraping Problem...' : 'Scrape Problem'}
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
                {data['problem-url'] && isValidUrl && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Preview</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <ExternalLink className="h-5 w-5 text-blue-500" />
                                <div>
                                    <p className="font-medium">Ready to scrape:</p>
                                    <a 
                                        href={data['problem-url']} 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                        className="text-blue-600 hover:underline text-sm"
                                    >
                                        {data['problem-url']}
                                    </a>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppLayout>
    );
} 