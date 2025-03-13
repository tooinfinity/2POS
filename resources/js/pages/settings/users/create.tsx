import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import React, { useMemo } from 'react';
import { BreadcrumbItem } from '@/types';
import { useLanguage } from '@/hooks/use-language';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ModelSelect } from '@/components/ui/model-select';
import { router } from '@inertiajs/react';
import { useForm } from '@inertiajs/react';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toast } from 'react-hot-toast';
import { cn } from '@/lib/utils';

interface Props {
    roles: {
        id: number;
        name: string;
    }[];
    permissions: {
        id: number;
        name: string;
        model?: string;
    }[];
    rolePermissions: {
        role: string;
        permissions: string[];
    }[];
}

export default function Create({ roles, permissions, rolePermissions }: Props) {
    const { __ } = useLanguage();
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: '',
        permission: '',
    });

    const breadcrumbs = useMemo<BreadcrumbItem[]>(
        () => [
            {
                title: __('Users'),
                href: '/settings/users',
            },
            {
                title: __('Create User'),
                href: '/settings/users/create',
            },
        ],
        [__],
    );

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();

        post('/settings/users', {
            preserveScroll: true,
            onSuccess: () => {
                toast.success(__('User created successfully'));
                router.visit('/settings/users');
            },
            onError: (errors: Record<string, string>) => {
                // Show specific error messages
                if (Object.keys(errors).length > 0) {
                    Object.values(errors).forEach((error) => {
                        toast.error(error);
                    });
                } else {
                    toast.error(__('Failed to create user'));
                }
            },
        });
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={__('Create User')} />
            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <Card>
                    <CardHeader>
                        <CardTitle>{__('Create User')}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="name">{__('Name')}</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    className={cn(errors.name && "border-destructive")}
                                />
                                {errors.name && (
                                    <p className="text-sm text-destructive">{errors.name}</p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="email">{__('Email')}</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                    className={cn(errors.email && "border-destructive")}
                                />
                                {errors.email && (
                                    <p className="text-sm text-destructive">{errors.email}</p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="password">{__('Password')}</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={e => setData('password', e.target.value)}
                                    className={cn(errors.password && "border-destructive")}
                                />
                                {errors.password && (
                                    <p className="text-sm text-destructive">{errors.password}</p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="password_confirmation">{__('Confirm Password')}</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={e => setData('password_confirmation', e.target.value)}
                                    className={cn(errors.password_confirmation && "border-destructive")}
                                />
                                {errors.password_confirmation && (
                                    <p className="text-sm text-destructive">{errors.password_confirmation}</p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="role">{__('Role')}</Label>
                                <Select value={data.role} onValueChange={(value) => setData('role', value)}>
                                    <SelectTrigger className={cn(errors.role && "border-destructive")}>
                                        <SelectValue placeholder={__('Select a role')} />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {roles.map((role) => (
                                            <SelectItem key={role.id} value={role.name}>
                                                {role.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.role && (
                                    <p className="text-sm text-destructive">{errors.role}</p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="permissions">{__('Permissions')}</Label>
                                <ModelSelect
                                    value={data.permission}
                                    onValueChange={(value) => setData('permission', value)}
                                    options={permissions.map((permission) => ({
                                        value: permission.name,
                                        label: permission.name,
                                        model: permission.model || 'Other',
                                    }))}
                                    placeholder={__('Select permission')}
                                    error={errors.permission}
                                    rolePermissions={rolePermissions}
                                    selectedRole={data.role}
                                />
                            </div>

                            <div className="flex justify-end gap-4">
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() => router.get('/settings/users')}
                                >
                                    {__('Cancel')}
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing ? __('Creating...') : __('Create User')}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
