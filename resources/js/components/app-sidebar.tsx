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
import { BookOpen, Box, Code, FileText, Home, ShieldCheck, User, Users } from 'lucide-react';
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
        title: 'Containers',
        href: '/containers',
        icon: Box,
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
            <SidebarHeader className="border-border/40 flex items-center justify-center border-b py-4">
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
                                    <AppLogoIcon className={`mx-auto h-30 w-30 object-contain transition-all duration-300`} />
                                ) : (
                                    <SLogoIcon className={`mx-auto h-16 w-16 object-contain transition-all duration-300`} />
                                )}
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent className="flex h-full flex-col justify-start space-y-6 py-8">
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter className="border-border/40 border-t">
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
