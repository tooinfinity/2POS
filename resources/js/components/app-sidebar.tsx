import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useLanguage } from '@/hooks/use-language';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid } from 'lucide-react';
import { useMemo } from 'react';
import AppLogo from './app-logo';

export function AppSidebar() {
    const { __ } = useLanguage();
    const mainNavItems: NavItem[] = useMemo<NavItem[]>(
        () => [
            {
                title: __('Dashboard'),
                url: '/dashboard',
                icon: LayoutGrid,
            },
        ],
        [__],
    );

    const footerNavItems: NavItem[] = useMemo<NavItem[]>(
        () => [
            {
                title: __('Repository'),
                url: 'https://github.com/laravel/react-starter-kit',
                icon: Folder,
            },
            {
                title: __('Documentation'),
                url: 'https://laravel.com/docs/starter-kits',
                icon: BookOpen,
            },
        ],
        [__],
    );
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
