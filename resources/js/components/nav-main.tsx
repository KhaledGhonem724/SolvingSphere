import { useSidebar } from '@/components/ui/sidebar';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';

export function NavMain({ items }: { items: NavItem[] }) {
    const { state } = useSidebar();
    const isCollapsed = state === 'collapsed';

    return (
        <nav className="space-y-1 px-2 py-2">
            {items.map((item) => {
                const Icon = item.icon;
                return (
                    <Link
                        key={item.href}
                        href={item.href}
                        className={cn(
                            'group flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors duration-200',
                            'hover:bg-accent hover:text-accent-foreground',
                            'text-muted-foreground',
                            isCollapsed ? 'justify-center' : '',
                        )}
                    >
                        {Icon && <Icon className={cn(
                            'mr-3', 
                            isCollapsed ? 'mr-0' : '',
                            state === 'collapsed' ? 'h-7  w-7' : 'h-5 w-5'
                        )} />}
                        {!isCollapsed && <span>{item.title}</span>}
                    </Link>
                );
            })}
        </nav>
    );
}
