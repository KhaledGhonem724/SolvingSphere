import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';

import { BookOpen, Box, Code, FileText, Home, ShieldCheck, User, Users, ListChecks, Route } from 'lucide-react';

import AppLogoIcon from './app-logo-icon';
import SLogoIcon from './charS-logo-icon';

const mainNavItems: NavItem[] = [
    {
        title: 'Home',
        href: '/',
        icon: Home,
    },
    {
        title: 'Problems',
        href: '/problems',
        icon: Code,
    },
    {
        title: 'Submissions',
        href: '/submissions',
        icon: FileText,
    },
    {
        title: 'Sheets',
        href: '/sheet',
        icon: FileText,
    },
      {
        title: 'Topics',
        href: '/topics',
        icon: FileText,
    },
      {
        title: 'Roadmaps',
        href: '/roadmaps',
        icon: FileText,
    },
    {
        title: 'Groups',
        href: '/groups',
        icon: Users,
    },
    {
        title: 'Blogs',
        href: '/blogs',
        icon: BookOpen,
    },
    {
        title: 'Profile',
        href: '/profile',
        icon: User,
    },
    {

        title: 'Admin',
        href: '/admin',
        icon: ShieldCheck,
    },
];

export function AppSidebar() {
    const { state } = useSidebar();
    const isExpanded = state === 'expanded';

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader className="flex justify-center items-center py-4 border-b border-border/40">
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            size="lg"
                            asChild
                            tooltip={{
                                content: 'Solving Sphere',
                                side: 'right',
                            }}
                        >
                            <Link href="/dashboard" prefetch>
                                {isExpanded ? (
                                    <AppLogoIcon className={`object-contain mx-auto transition-all duration-300 h-30 w-30`} />
                                ) : (
                                    <SLogoIcon className={`object-contain mx-auto w-16 h-16 transition-all duration-300`} />
                                )}
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent className="flex flex-col justify-start py-8 space-y-6 h-full">
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter className="border-t border-border/40">
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
