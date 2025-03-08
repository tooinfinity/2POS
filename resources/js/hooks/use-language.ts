import { router, usePage } from '@inertiajs/react';
import { useCallback, useEffect, useState } from 'react';

export type Language = 'en' | 'fr' | 'ar';

interface PageProps {
    language: { [key: string]: string };
    locale: string;
}

const applyLanguage = (locale: Language) => {
    router.put(
        route('language.update'),
        { language: locale },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                router.visit(window.location.pathname, {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                });
            },
            onError: (errors) => {
                console.error('Language update failed:', errors);
            },
        },
    );
};

export function initializeLanguage() {
    const savedLanguage = (localStorage.getItem('language') as Language) || 'en';
    applyLanguage(savedLanguage);
}

export function useLanguage() {
    const { locale: pageLocale, language } = usePage<{ props: PageProps }>().props;
    const [locale, setLocale] = useState<Language>(() => {
        const savedLanguage = localStorage.getItem('language') as Language;
        return savedLanguage || (pageLocale as Language) || 'en';
    });

    const updateLanguage = useCallback(
        (newLocale: Language) => {
            if (newLocale === locale) return;
            setLocale(newLocale);
            localStorage.setItem('language', newLocale);
            applyLanguage(newLocale);
        },
        [locale],
    );

    const __ = (key: string, replace: Record<string, string | number> = {}) => {
        const translations = language as Record<string, string>;
        let translation: string = translations[key] || key;

        Object.entries(replace).forEach(([key, value]) => {
            translation = translation.replace(`:${key}`, String(value));
        });

        return translation;
    };

    useEffect(() => {
        const savedLanguage = localStorage.getItem('language') as Language;
        if (savedLanguage && savedLanguage !== locale) {
            updateLanguage(savedLanguage);
        }
    }, [locale, updateLanguage]);

    return { locale, updateLanguage, __ };
}
