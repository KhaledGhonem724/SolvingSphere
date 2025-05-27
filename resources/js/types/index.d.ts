import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    user_handle: string;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    role: 'admin' | 'user';
    solved_problems: number;
    last_active_day: string | null;
    streak_days: number;
    maximum_streak_days: number;
    social_score: number;
    technical_score: number;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}
