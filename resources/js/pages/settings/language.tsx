import HeadingSmall from '@/components/heading-small';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { type Language, useLanguage } from '@/hooks/use-language';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useMemo } from 'react';

export default function Language() {
    const { locale, updateLanguage, __ } = useLanguage();

    const breadcrumbs = useMemo<BreadcrumbItem[]>(
        () => [
            {
                title: __('Language settings'),
                href: '/settings/language',
            },
        ],
        [__],
    );

    const handleLanguageChange = (value: Language) => {
        updateLanguage(value);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={__('Language settings')} />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title={__('Language preferences')}
                        description={__('Choose your preferred language for the application interface')}
                    />
                    <div className="grid gap-2">
                        <Label htmlFor="language">{__('Select Language')}</Label>

                        <Select value={locale} onValueChange={handleLanguageChange}>
                            <SelectTrigger className="w-[180px]">
                                <SelectValue placeholder={__('Select Language')} />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="en">{__('English')}</SelectItem>
                                    <SelectItem value="fr">{__('French')}</SelectItem>
                                    <SelectItem value="ar">{__('Arabic')}</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
