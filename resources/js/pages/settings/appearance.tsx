import { Head } from '@inertiajs/react';

import AppearanceTabs from '@/components/appearance-tabs';
import HeadingSmall from '@/components/heading-small';
import { type BreadcrumbItem } from '@/types';

import { useLanguage } from '@/hooks/use-language';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { useMemo } from 'react';

export default function Appearance() {
    const { __ } = useLanguage();
    const breadcrumbs = useMemo<BreadcrumbItem[]>(
        () => [
            {
                title: __('Appearance settings'),
                href: '/settings/appearance',
            },
        ],
        [__],
    );
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={__('Appearance settings')} />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title={__('Appearance settings')} description={__('Update your account appearance settings')} />
                    <AppearanceTabs />
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
