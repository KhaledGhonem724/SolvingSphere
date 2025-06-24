import React, { useState, useEffect } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { AlertCircle, CheckCircle, Loader2, RefreshCw, X, Code, Trophy, Target, BarChart } from 'lucide-react';
import { Alert, AlertDescription } from '@/components/ui/alert';
import axios from 'axios';

interface HackerEarthData {
    username: string;
    points: number;
    contest_rating: number;
    problems_solved: number;
    solutions_submitted: number;
    connected_at: string;
    updated_at: string;
}

interface HackerEarthConnectionProps {
    initialData?: HackerEarthData | null;
    onUpdate?: (data: HackerEarthData | null) => void;
}

const STORAGE_KEY = 'hackerearth_connection_status';

export default function HackerEarthConnection({ initialData, onUpdate }: HackerEarthConnectionProps) {
    const [isConnected, setIsConnected] = useState(!!initialData);
    const [hackerEarthData, setHackerEarthData] = useState<HackerEarthData | null>(initialData || null);
    const [isLoading, setIsLoading] = useState(false);
    const [isRefreshing, setIsRefreshing] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [success, setSuccess] = useState<string | null>(null);
    const [formData, setFormData] = useState({
        username: '',
        password: '',
    });

    // Check localStorage on component mount
    useEffect(() => {
        const savedConnectionStatus = localStorage.getItem(STORAGE_KEY);
        if (savedConnectionStatus) {
            try {
                const parsedData = JSON.parse(savedConnectionStatus);
                const isDataValid = parsedData.isConnected && parsedData.data && parsedData.data.username && parsedData.timestamp;

                // Check if data is not too old (optional: expire after 7 days)
                const SEVEN_DAYS = 7 * 24 * 60 * 60 * 1000;
                const isNotExpired = parsedData.timestamp && Date.now() - parsedData.timestamp < SEVEN_DAYS;

                if (isDataValid && isNotExpired) {
                    setIsConnected(true);
                    setHackerEarthData(parsedData.data);
                    // Also update parent component if initial data wasn't provided
                    if (!initialData && onUpdate) {
                        onUpdate(parsedData.data);
                    }
                } else {
                    // Clear expired or invalid data
                    localStorage.removeItem(STORAGE_KEY);
                }
                /* eslint-disable */
            } catch (error) {
                // If parsing fails, clear the corrupted data
                localStorage.removeItem(STORAGE_KEY);
            }
        }
    }, []); // Empty dependency array - only run once on mount

    // Update localStorage whenever connection status changes
    useEffect(() => {
        if (isConnected && hackerEarthData) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                isConnected: true,
                data: hackerEarthData,
                timestamp: Date.now()
            }));
        } else if (!isConnected) {
            localStorage.removeItem(STORAGE_KEY);
        }
    }, [isConnected, hackerEarthData]); // Only run when these specific values change

    const clearMessages = () => {
        setError(null);
        setSuccess(null);
    };

    const updateConnectionState = (data: HackerEarthData | null, message?: string) => {
        setHackerEarthData(data);
        setIsConnected(!!data);
        if (onUpdate) {
            onUpdate(data);
        }
        if (message) {
            setSuccess(message);
        }
    };

    const handleConnect = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!formData.username || !formData.password) {
            setError('Please fill in all fields');
            return;
        }

        setIsLoading(true);
        clearMessages();

        try {
            const response = await axios.post('/api/hackerearth/connect', {
                username: formData.username,
                password: formData.password,
            });

            if (response.data.success) {
                updateConnectionState(response.data.data, response.data.message);
                setFormData({ username: '', password: '' });
            }
        } catch (err: any) {
            const errorMessage = err.response?.data?.message || 'Failed to connect to HackerEarth';
            setError(errorMessage);
        } finally {
            setIsLoading(false);
        }
    };

    const handleRefresh = async () => {
        if (!formData.password) {
            setError('Please enter your password to refresh data');
            return;
        }

        setIsRefreshing(true);
        clearMessages();

        try {
            const response = await axios.post('/api/hackerearth/refresh', {
                password: formData.password,
            });

            if (response.data.success) {
                updateConnectionState(response.data.data, response.data.message);
                setFormData({ username: '', password: '' });
            }
            /* eslint-disable */
        } catch (err: any) {
            const errorMessage = err.response?.data?.message || 'Failed to refresh HackerEarth data';
            setError(errorMessage);
        } finally {
            setIsRefreshing(false);
        }
    };

    const handleDisconnect = async () => {
        setIsLoading(true);
        clearMessages();

        try {
            const response = await axios.delete('/api/hackerearth/disconnect');

            if (response.data.success) {
                updateConnectionState(null, response.data.message);
                setFormData({ username: '', password: '' });
            }
        } catch (err: any) {
            const errorMessage = err.response?.data?.message || 'Failed to disconnect HackerEarth account';
            setError(errorMessage);
        } finally {
            setIsLoading(false);
        }
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    return (
        <Card>
            <CardHeader>
                <CardTitle className="flex items-center space-x-2">
                    <Code className="h-5 w-5 text-orange-500" />
                    <span>HackerEarth Integration</span>
                </CardTitle>
                <CardDescription>
                    Connect your HackerEarth account to display your coding achievements
                </CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
                {/* Error/Success Messages */}
                {error && (
                    <Alert variant="destructive">
                        <AlertCircle className="h-4 w-4" />
                        <AlertDescription>{error}</AlertDescription>
                    </Alert>
                )}

                {success && (
                    <Alert className="border-green-200 bg-green-50">
                        <CheckCircle className="h-4 w-4 text-green-600" />
                        <AlertDescription className="text-green-700">{success}</AlertDescription>
                    </Alert>
                )}

                {isConnected && hackerEarthData ? (
                    /* Connected State - Display Profile Data */
                    <div className="space-y-4">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center space-x-2">
                                <Badge variant="outline" className="bg-green-50 text-green-700 border-green-200">
                                    <CheckCircle className="h-3 w-3 mr-1" />
                                    Connected
                                </Badge>
                                <span className="font-medium">{hackerEarthData.username}</span>
                            </div>
                            <Button
                                variant="ghost"
                                size="sm"
                                onClick={handleDisconnect}
                                disabled={isLoading}
                                className="text-red-600 hover:text-red-700 hover:bg-red-50"
                            >
                                <X className="h-4 w-4 mr-1" />
                                Disconnect
                            </Button>
                        </div>

                        <Separator />

                        {/* HackerEarth Statistics */}
                        <div className="grid grid-cols-2 gap-4">
                            <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                <div className="flex items-center space-x-2">
                                    <Trophy className="h-5 w-5 text-yellow-500" />
                                    <span className="text-sm font-medium">Points</span>
                                </div>
                                <span className="text-2xl font-bold">{hackerEarthData.points?.toLocaleString() || 0}</span>
                            </div>

                            <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                <div className="flex items-center space-x-2">
                                    <BarChart className="h-5 w-5 text-blue-500" />
                                    <span className="text-sm font-medium">Contest Rating</span>
                                </div>
                                <span className="text-2xl font-bold">{hackerEarthData.contest_rating || 0}</span>
                            </div>

                            <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                <div className="flex items-center space-x-2">
                                    <Target className="h-5 w-5 text-green-500" />
                                    <span className="text-sm font-medium">Problems Solved</span>
                                </div>
                                <span className="text-2xl font-bold">{hackerEarthData.problems_solved || 0}</span>
                            </div>

                            <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                <div className="flex items-center space-x-2">
                                    <Code className="h-5 w-5 text-purple-500" />
                                    <span className="text-sm font-medium">Solutions Submitted</span>
                                </div>
                                <span className="text-2xl font-bold">{hackerEarthData.solutions_submitted || 0}</span>
                            </div>
                        </div>

                        {/* Refresh Section */}
                        <div className="space-y-3 pt-4 border-t">
                            <Label htmlFor="refresh-password">Enter password to refresh data:</Label>
                            <div className="flex space-x-2">
                                <Input
                                    id="refresh-password"
                                    type="password"
                                    placeholder="HackerEarth password"
                                    value={formData.password}
                                    onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                                />
                                <Button
                                    onClick={handleRefresh}
                                    disabled={isRefreshing || !formData.password}
                                    size="sm"
                                >
                                    {isRefreshing ? (
                                        <Loader2 className="h-4 w-4 animate-spin mr-1" />
                                    ) : (
                                        <RefreshCw className="h-4 w-4 mr-1" />
                                    )}
                                    Refresh
                                </Button>
                            </div>
                        </div>

                        {/* Last Updated */}
                        {hackerEarthData.updated_at && (
                            <p className="text-xs text-muted-foreground text-center">
                                Last updated: {formatDate(hackerEarthData.updated_at)}
                            </p>
                        )}
                    </div>
                ) : (
                    /* Disconnected State - Connection Form */
                    <form onSubmit={handleConnect} className="space-y-4">
                        <div className="space-y-2">
                            <Label htmlFor="username">HackerEarth Username</Label>
                            <Input
                                id="username"
                                type="text"
                                placeholder="Enter your HackerEarth username"
                                value={formData.username}
                                onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                                disabled={isLoading}
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="password">HackerEarth Password</Label>
                            <Input
                                id="password"
                                type="password"
                                placeholder="Enter your HackerEarth password"
                                value={formData.password}
                                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                                disabled={isLoading}
                            />
                        </div>

                        <Button type="submit" disabled={isLoading} className="w-full">
                            {isLoading ? (
                                <>
                                    <Loader2 className="h-4 w-4 animate-spin mr-2" />
                                    Connecting...
                                </>
                            ) : (
                                'Connect Account'
                            )}
                        </Button>

                        <p className="text-xs text-muted-foreground">
                            Your credentials are only used to authenticate with HackerEarth and are not stored.
                        </p>
                    </form>
                )}
            </CardContent>
        </Card>
    );
} 