import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { FormEventHandler } from 'react';

interface UserFormData {
    first_name: string;
    last_name: string;
    email: string;
    document_id: string;
    phone_number: string;
    status: 'pending' | 'active' | 'suspended';
    password: string;
    password_confirmation: string;
}

interface Props {
    user?: Partial<UserFormData>;
    submitUrl: string;
    method: 'post' | 'put';
}

export default function UserForm({ user, submitUrl, method }: Props) {
    const { data, setData, post, put, processing, errors, reset } = useForm<UserFormData>({
        first_name: user?.first_name || '',
        last_name: user?.last_name || '',
        email: user?.email || '',
        document_id: user?.document_id || '',
        phone_number: user?.phone_number || '',
        status: user?.status || 'pending',
        password: '',
        password_confirmation: '',
    });

    const handleSubmit: FormEventHandler = (e) => {
        e.preventDefault();

        if (method === 'post') {
            post(submitUrl, {
                onSuccess: () => reset('password', 'password_confirmation'),
            });
        } else {
            put(submitUrl, {
                onSuccess: () => reset('password', 'password_confirmation'),
            });
        }
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-6">
            <div className="grid gap-4 md:grid-cols-2">
                <div className="space-y-2">
                    <Label htmlFor="first_name">First Name *</Label>
                    <Input
                        id="first_name"
                        value={data.first_name}
                        onChange={(e) => setData('first_name', e.target.value)}
                        className={errors.first_name ? 'border-red-500' : ''}
                    />
                    {errors.first_name && (
                        <p className="text-sm text-red-500">{errors.first_name}</p>
                    )}
                </div>

                <div className="space-y-2">
                    <Label htmlFor="last_name">Last Name *</Label>
                    <Input
                        id="last_name"
                        value={data.last_name}
                        onChange={(e) => setData('last_name', e.target.value)}
                        className={errors.last_name ? 'border-red-500' : ''}
                    />
                    {errors.last_name && (
                        <p className="text-sm text-red-500">{errors.last_name}</p>
                    )}
                </div>
            </div>

            <div className="space-y-2">
                <Label htmlFor="email">Email *</Label>
                <Input
                    id="email"
                    type="email"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    className={errors.email ? 'border-red-500' : ''}
                />
                {errors.email && (
                    <p className="text-sm text-red-500">{errors.email}</p>
                )}
            </div>

            <div className="grid gap-4 md:grid-cols-2">
                <div className="space-y-2">
                    <Label htmlFor="document_id">Document ID</Label>
                    <Input
                        id="document_id"
                        value={data.document_id}
                        onChange={(e) => setData('document_id', e.target.value)}
                        className={errors.document_id ? 'border-red-500' : ''}
                    />
                    {errors.document_id && (
                        <p className="text-sm text-red-500">{errors.document_id}</p>
                    )}
                </div>

                <div className="space-y-2">
                    <Label htmlFor="phone_number">Phone Number</Label>
                    <Input
                        id="phone_number"
                        value={data.phone_number}
                        onChange={(e) => setData('phone_number', e.target.value)}
                        className={errors.phone_number ? 'border-red-500' : ''}
                    />
                    {errors.phone_number && (
                        <p className="text-sm text-red-500">{errors.phone_number}</p>
                    )}
                </div>
            </div>

            <div className="space-y-2">
                <Label htmlFor="status">Status *</Label>
                <Select
                    value={data.status}
                    onValueChange={(value) => setData('status', value as any)}
                >
                    <SelectTrigger className={errors.status ? 'border-red-500' : ''}>
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="pending">Pending</SelectItem>
                        <SelectItem value="active">Active</SelectItem>
                        <SelectItem value="suspended">Suspended</SelectItem>
                    </SelectContent>
                </Select>
                {errors.status && (
                    <p className="text-sm text-red-500">{errors.status}</p>
                )}
            </div>

            <div className="grid gap-4 md:grid-cols-2">
                <div className="space-y-2">
                    <Label htmlFor="password">
                        Password {user ? '(leave blank to keep current)' : '*'}
                    </Label>
                    <Input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className={errors.password ? 'border-red-500' : ''}
                        autoComplete="new-password"
                    />
                    {errors.password && (
                        <p className="text-sm text-red-500">{errors.password}</p>
                    )}
                </div>

                <div className="space-y-2">
                    <Label htmlFor="password_confirmation">
                        Confirm Password {!user && '*'}
                    </Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        className={errors.password_confirmation ? 'border-red-500' : ''}
                        autoComplete="new-password"
                    />
                    {errors.password_confirmation && (
                        <p className="text-sm text-red-500">{errors.password_confirmation}</p>
                    )}
                </div>
            </div>

            <div className="flex justify-end gap-4">
                <Button
                    type="button"
                    variant="outline"
                    onClick={() => window.history.back()}
                    disabled={processing}
                >
                    Cancel
                </Button>
                <Button type="submit" disabled={processing}>
                    {processing ? 'Saving...' : user ? 'Update User' : 'Create User'}
                </Button>
            </div>
        </form>
    );
}
