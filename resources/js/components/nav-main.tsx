import { useSidebar } from '@/components/ui/sidebar';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';

export function NavMain({ items }: { items: NavItem[] }) {
    const { state } = useSidebar();
    const isCollapsed = state === 'collapsed';

    return (
        <nav className="flex h-full flex-col justify-between space-y-4 px-2 py-4" data-sidebar-state={state}>
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
                        {Icon && (
                            <Icon
                                className={cn(
                                    'mr-3',
                                    isCollapsed ? 'mr-0 size-10' : 'size-4 group-data-[sidebar-state=expanded]:size-7',
                                    'group-hover:text-accent-foreground',
                                )}
                            />
                        )}
                        {!isCollapsed && <span className={cn('text-xs', 'group-data-[sidebar-state=expanded]:text-[0.65rem]')}>{item.title}</span>}
                    </Link>
                );
            })}
        </nav>
    );
}
