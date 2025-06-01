import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { CalendarDays, Check, Flame, Github, Linkedin, Trophy, User as UserIcon } from 'lucide-react';

import HackerEarthConnection from '@/components/HackerEarthConnection';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile',
        href: '/profile',
    },
];

interface ProfileProps {
    // In a real application, these would come from the backend
    statistics: {
        solvedProblems: number;
        lastActiveDay: string;
        streakDays: number;
        maxStreakDays: number;
        technicalScore: number;
        socialScore: number;
    };
    badges: Array<{
        id: number;
        name: string;
        description: string;
    }>;
    socialLinks: {
        linkedin?: string;
        github?: string;
        portfolio?: string;
    };
    hackerEarthData?: {
        username: string;
        points: number;
        contest_rating: number;
        problems_solved: number;
        solutions_submitted: number;
        connected_at: string;
        updated_at: string;
    } | null;
}

export default function Profile({ statistics, badges, socialLinks, hackerEarthData }: ProfileProps) {
    /* eslint-disable */
    const { auth } = usePage().props as any as SharedData;
    const user = auth.user;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="My Profile" />

            <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
                {/* Left sidebar - Personal info */}
                <Card className="md:col-span-1">
                    <CardHeader>
                        <CardTitle>Personal Info</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="flex flex-col items-center space-y-3">
                            <div className="relative">
                                <div className="h-24 w-24 overflow-hidden rounded-full bg-slate-100">
                                    {user.avatar ? (
                                        <img src={user.avatar} alt={user.name} className="h-full w-full object-cover" />
                                    ) : (
                                        <div className="flex h-full w-full items-center justify-center">
                                            <UserIcon className="h-12 w-12 text-slate-400" />
                                        </div>
                                    )}
                                </div>
                            </div>
                            <div className="text-center">
                                <h3 className="text-lg font-medium">{user.name}</h3>
                                <p className="text-lg font-medium">{user.user_handle}</p>
                            </div>
                        </div>

                        <Separator />

                        <div className="space-y-3">
                            <h4 className="text-sm font-medium">Social Links</h4>

                            {socialLinks.linkedin && (
                                <div className="flex items-center space-x-2">
                                    <Linkedin className="h-5 w-5 text-blue-600" />
                                    <a href={socialLinks.linkedin} target="_blank" rel="noopener noreferrer" className="text-sm hover:underline">
                                        LinkedIn
                                    </a>
                                </div>
                            )}

                            {socialLinks.github && (
                                <div className="flex items-center space-x-2">
                                    <Github className="h-5 w-5" />
                                    <a href={socialLinks.github} target="_blank" rel="noopener noreferrer" className="text-sm hover:underline">
                                        GitHub
                                    </a>
                                </div>
                            )}

                            {socialLinks.portfolio && (
                                <div className="flex items-center space-x-2">
                                    <UserIcon className="h-5 w-5 text-green-600" />
                                    <a href={socialLinks.portfolio} target="_blank" rel="noopener noreferrer" className="text-sm hover:underline">
                                        Portfolio
                                    </a>
                                </div>
                            )}
                        </div>

                        <Separator />

                        <div className="space-y-2">
                            <Link href={route('profile.edit')} className="w-full">
                                <Button variant="outline" size="sm" className="w-full">
                                    Edit Profile
                                </Button>
                            </Link>
                            <Link href={route('settings.personal-info')} className="w-full">
                                <Button variant="outline" size="sm" className="w-full">
                                    Edit Personal Info
                                </Button>
                            </Link>
                        </div>
                    </CardContent>
                </Card>

                {/* Main content */}
                <div className="space-y-6 md:col-span-2">
                    {/* Statistics section */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Statistics</CardTitle>
                            <CardDescription>Your coding journey in numbers</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                {/* Solved Problems */}
                                <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                    <div className="flex items-center space-x-2">
                                        <Check className="h-5 w-5 text-green-500" />
                                        <span className="text-sm font-medium">Solved Problems</span>
                                    </div>
                                    <span className="text-2xl font-bold">{statistics.solvedProblems}</span>
                                </div>

                                {/* Active Days */}
                                <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                    <div className="flex items-center space-x-2">
                                        <CalendarDays className="h-5 w-5 text-blue-500" />
                                        <span className="text-sm font-medium">Last Active</span>
                                    </div>
                                    <span className="text-lg font-medium">{statistics.lastActiveDay}</span>
                                </div>

                                {/* Current Streak */}
                                <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                    <div className="flex items-center space-x-2">
                                        <Flame className="h-5 w-5 text-orange-500" />
                                        <span className="text-sm font-medium">Current Streak</span>
                                    </div>
                                    <span className="text-2xl font-bold">{statistics.streakDays} days</span>
                                </div>

                                {/* Max Streak */}
                                <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                    <div className="flex items-center space-x-2">
                                        <Trophy className="h-5 w-5 text-yellow-500" />
                                        <span className="text-sm font-medium">Max Streak</span>
                                    </div>
                                    <span className="text-2xl font-bold">{statistics.maxStreakDays} days</span>
                                </div>

                                {/* Technical Score */}
                                <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                    <div className="flex items-center space-x-2">
                                        <svg
                                            className="h-5 w-5 text-purple-500"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            strokeWidth="2"
                                        >
                                            <path d="M12 4L4 8l8 4 8-4-8-4z" />
                                            <path d="M4 12l8 4 8-4" />
                                            <path d="M4 16l8 4 8-4" />
                                        </svg>
                                        <span className="text-sm font-medium">Technical Score</span>
                                    </div>
                                    <span className="text-2xl font-bold">{statistics.technicalScore}</span>
                                </div>

                                {/* Social Score */}
                                <div className="flex flex-col space-y-2 rounded-lg border p-4">
                                    <div className="flex items-center space-x-2">
                                        <svg className="h-5 w-5 text-pink-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                        <span className="text-sm font-medium">Social Score</span>
                                    </div>
                                    <span className="text-2xl font-bold">{statistics.socialScore}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Badges section */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Badges</CardTitle>
                            <CardDescription>Your achievements and recognition</CardDescription>
                        </CardHeader>
                        <CardContent>
                            {badges.length === 0 ? (
                                <p className="text-muted-foreground py-6 text-center">
                                    You haven't earned any badges yet. Keep solving problems to earn badges!
                                </p>
                            ) : (
                                <div className="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                    {badges.map((badge) => (
                                        <div key={badge.id} className="flex flex-col items-center space-y-2 rounded-lg border p-4 text-center">
                                            <Badge className="flex h-12 w-12 items-center justify-center rounded-full text-lg">
                                                {badge.name.charAt(0)}
                                            </Badge>
                                            <span className="font-medium">{badge.name}</span>
                                            <p className="text-muted-foreground text-xs">{badge.description}</p>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </CardContent>
                    </Card>

                    {/* HackerEarth Integration */}
                    <HackerEarthConnection initialData={hackerEarthData} />
                </div>
            </div>
        </AppLayout>
    );
}
