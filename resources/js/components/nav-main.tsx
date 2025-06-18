import { useSidebar } from '@/components/ui/sidebar';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';

export function NavMain({ items }: { items: NavItem[] }) {
    const { state } = useSidebar();
    const isCollapsed = state === 'collapsed';

    return (
        <nav className="flex h-full flex-col justify-between space-y-4 px-2 py-4">
            {items.map((item) => {
                const Icon = item.icon;
                return (
                    <Link
                        key={item.href}
                        href={item.href}
                        className={cn(
                            'group flex items-center rounded-md px-3 py-3 text-base font-semibold transition-colors duration-200',
                            'hover:bg-accent hover:text-accent-foreground',
                            'text-muted-foreground',
                            isCollapsed ? 'justify-center' : '',
                        )}
                    >
                        {Icon && <Icon className={cn('mr-4', isCollapsed ? 'mr-0' : '', state === 'collapsed' ? 'h-8 w-8' : 'h-6 w-6')} />}
                        {!isCollapsed && <span>{item.title}</span>}
                    </Link>
                );
            })}
        </nav>
    );
}
