import { buttonVariants } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';

export function NavMain({ items }: { items: NavItem[] }) {
    return (
        <nav className="grid gap-2">
            {items.map((item) => (
                <Link
                    key={item.title}
                    href={item.href}
                    className={cn(buttonVariants({ variant: 'ghost' }), 'justify-start', 'hover:bg-accent', 'hover:text-accent-foreground')}
                    prefetch
                >
                    <item.icon className="mr-2 size-4" />
                    {item.title}
                </Link>
            ))}
        </nav>
    );
}
