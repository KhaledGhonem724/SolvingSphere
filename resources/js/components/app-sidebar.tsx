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
import { BookOpen, Folder, LayoutGrid, User, FileText } from 'lucide-react';
import AppLogoIcon from './app-logo-icon';
import SLogoIcon from './charS-logo-icon';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Profile',
        href: '/profile',
        icon: User,
    },
    {
        title: 'Sheets',
        href: '/sheet',
        icon: FileText,
    },
    {
        title: 'Blogs',
        href: '/blogs',
        icon: BookOpen,
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

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter className="border-border/40 border-t">
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
