import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import UserForm from '@/pages/Admin/Users/Partials/Form';

interface User {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    document_id?: string;
    phone_number?: string;
    status: 'pending' | 'active' | 'suspended';
}

interface Props {
    user: User;
}

export default function EditUser({ user }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Users', href: '/users' },
        { title: 'Edit', href: `/users/${user.id}/edit` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit User" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <h1 className="text-2xl font-bold">
                    Edit User: {user.first_name} {user.last_name}
                </h1>
                <div className="rounded-lg border p-6">
                    <UserForm
                        user={user}
                        submitUrl={`/users/${user.id}`}
                        method="put"
                    />
                </div>
            </div>
        </AppLayout>
    );
}
