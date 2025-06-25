import { useSidebar } from '@/components/ui/sidebar';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';

export function NavMain({ items }: { items: NavItem[] }) {
    const { state } = useSidebar();
    const isCollapsed = state === 'collapsed';

    return (
        <nav className="flex h-full flex-col justify-between space-y-2 px-1 py-2">
            {items.map((item) => {
                const Icon = item.icon;
                return (
                    <Link
                        key={item.href}
                        href={item.href}
                        className={cn(
                            'group flex items-center rounded-md px-2 py-2 font-semibold transition-all duration-200',
                            'hover:bg-accent hover:text-accent-foreground',
                            'text-muted-foreground',
                            isCollapsed ? 'justify-center' : '',
                        )}
                    >
                        {Icon && (
                            <Icon
                                className={cn(
                                    isCollapsed
                                        ? 'h-7 w-7 transition-all duration-200'
                                        : 'h-4 w-4 transition-all duration-200 mr-1',
                                )}
                            />
                        )}
                        {!isCollapsed && (
                            <span className="transition-all duration-200 text-xs">{item.title}</span>
                        )}
                    </Link>
                );
            })}
        </nav>
    );
}
