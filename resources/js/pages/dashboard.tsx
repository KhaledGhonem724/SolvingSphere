import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];

export default function Dashboard() {
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Dashboard" />

      <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        {/* Cards section */}
        <div className="grid auto-rows-min gap-4 md:grid-cols-3">
          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-sidebar-border/70 dark:border-sidebar-border">
            <h3 className="text-lg font-medium text-gray-700 dark:text-white mb-2">My Orders</h3>
            <p className="text-2xl font-bold text-blue-600">12</p>
          </div>
          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-sidebar-border/70 dark:border-sidebar-border">
            <h3 className="text-lg font-medium text-gray-700 dark:text-white mb-2">Balance</h3>
            <p className="text-2xl font-bold text-green-600">$340.50</p>
          </div>
          <div className="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-sidebar-border/70 dark:border-sidebar-border">
            <h3 className="text-lg font-medium text-gray-700 dark:text-white mb-2">Messages</h3>
            <p className="text-2xl font-bold text-purple-600">4</p>
          </div>
        </div>

        {/* Recent Activity Section */}
        <div className="bg-white dark:bg-gray-800 rounded-xl p-6 border border-sidebar-border/70 dark:border-sidebar-border">
          <h2 className="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Recent Activity</h2>
          <ul className="space-y-2 text-gray-600 dark:text-gray-300 text-sm">
            <li>✅ Order #1245 confirmed</li>
            <li>📦 Package #4456 shipped</li>
            <li>💬 New message from support</li>
          </ul>
        </div>
      </div>
    </AppLayout>
  );
}
