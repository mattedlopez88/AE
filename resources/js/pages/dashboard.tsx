import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const quickLinks = [
    {
        title: 'Manage Users',
        description: 'View and manage all users',
        href: '/users',
        // icon: Users,
        color: 'bg-blue-500',
    },
    {
        title: 'Cases',
        description: 'View all legal cases',
        href: '/cases',
        // icon: FileText,
        color: 'bg-green-500',
    },
    {
        title: 'Calendar',
        description: 'View appointments',
        href: '/calendar',
        // icon: Calendar,
        color: 'bg-purple-500',
    },
    {
        title: 'Settings',
        description: 'System settings',
        href: '/settings',
        // icon: Settings,
        color: 'bg-orange-500',
    },
];

export default function Dashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="mb-4">
                    <h2 className="text-2xl font-bold mb-2">Quick Actions</h2>
                    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        {quickLinks.map((link) => {
                            // const Icon = link.icon;
                            return (
                                <Link key={link.href} href={link.href}>
                                    <div className="group relative overflow-hidden rounded-lg border p-4 hover:shadow-md transition-shadow cursor-pointer">
                                        <div className="flex items-start gap-4">
                                            {/*<div className={`${link.color} p-3 rounded-lg`}>*/}
                                            {/*    <Icon className="h-6 w-6 text-white" />*/}
                                            {/*</div>*/}
                                            <div className="flex-1">
                                                <h3 className="font-semibold mb-1 group-hover:text-primary transition-colors">
                                                    {link.title}
                                                </h3>
                                                <p className="text-sm text-muted-foreground">
                                                    {link.description}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            );
                        })}
                    </div>
                </div>
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                </div>
                <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
        </AppLayout>
    );
}
