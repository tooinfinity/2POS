import React, { useMemo } from 'react';
import { useLanguage } from '@/hooks/use-language';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import DataTable from '@/components/data-table';
import { ColumnDef } from '@tanstack/react-table';
import ColumnHeader from '@/components/column-header';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Edit, MoreHorizontal, Trash } from 'lucide-react';
import { router } from '@inertiajs/react';
import { toast } from 'react-hot-toast';
import { cn } from '@/lib/utils';

interface User {
    id: number;
    name: string;
    email: string;
    roles: string[];
    permissions: string[];
}

interface Props {
    users: {
        data: User[];
    };
}

export default function Index({ users }: Props) {
    const { __ } = useLanguage();
    const breadcrumbs = useMemo<BreadcrumbItem[]>(
        () => [
            {
                title: __('Users'),
                href: '/settings/users',
            },
        ],
        [__],
    );

    const columns = useMemo<ColumnDef<User>[]>(
        () => [
            {
                header: ({ column }) => <ColumnHeader column={column} title={__('Name')} />,
                accessorKey: 'name',
            },
            {
                header: ({ column }) => <ColumnHeader column={column} title={__('Email')} />,
                accessorKey: 'email',
            },
            {
                header: ({ column }) => <ColumnHeader column={column} title={__('Roles')} />,
                accessorKey: 'roles',
                cell: ({ row }) => (
                    <div className="flex gap-1 flex-wrap">
                        {row.original.roles.map((role, index) => (
                            <p key={`${row.original.id}-${role}-${index}`} className={cn('px-2 py-1 rounded-md text-sm font-medium', {
                                'bg-red-100 text-red-700': role.toLowerCase() === 'administrator',
                                'bg-blue-100 text-blue-700': role.toLowerCase() === 'manager',
                                'bg-orange-100 text-orange-700': role.toLowerCase() === 'cashier',
                                'bg-gray-100 text-gray-700': !['administrator', 'manager', 'cashier'].includes(role.toLowerCase())
                            })}>{role}</p>
                        ))}
                    </div>
                )
            },
            {
                id: 'actions',
                header: ({ column }) => <ColumnHeader column={column} title={__('Actions')} />,
                cell: ({ row }) => {
                    const user = row.original;
                    return (
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="ghost" className="h-8 w-8 p-0">
                                    <span className="sr-only">{__('Open menu')}</span>
                                    <MoreHorizontal className="h-4 w-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuLabel>{__('Actions')}</DropdownMenuLabel>
                                <DropdownMenuItem onClick={() => router.get(`/settings/users/${user.id}/edit`)}>
                                    <Edit className="mr-2 h-4 w-4" /> {__('Edit')}
                                </DropdownMenuItem>
                                <DropdownMenuItem
                                    onClick={() => {
                                        if (confirm(__('Are you sure you want to delete this user?'))) {
                                            router.delete(`/settings/users/${user.id}`, {
                                                onSuccess: () => toast.success(__('User deleted successfully')),
                                            });
                                        }
                                    }}
                                    className="text-red-600"
                                >
                                    <Trash className="mr-2 h-4 w-4" /> {__('Delete')}
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    );
                },
            },
        ],
        [__],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={__('Users')} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className=" space-y-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-2xl font-bold tracking-tight">{__('Users')}</h2>
                            <p className="text-muted-foreground">
                                {__('Manage user accounts and their permissions')}
                            </p>
                        </div>
                        <Button onClick={() => router.get('/settings/users/create')}>
                            {__('Add User')}
                        </Button>
                    </div>

                    <DataTable columns={columns} data={users.data} />
                </div>
            </div>
        </AppLayout>
    );
}
