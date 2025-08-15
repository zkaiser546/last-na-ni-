<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpenCheck, LayoutGrid, Library, Users, BookOpen, FileText, ChevronDown, Folder, EthernetPortIcon, Logs, Boxes } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { FileClock  } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },

    {
        title: 'Users',
        href: '/users',
        icon: Users,
    },
    {
        title: 'Records',
        href: '/records',
        icon: Library,
    },
    {
        title: 'Borrowings',
        href: '/borrowings',
        icon: BookOpenCheck,
    },
    {
        title: 'Visitors',
        href: '/logger',
        icon: FileClock,
    },
    {
        title: 'Reports',
        icon: FileText,
        hasSubmenu: true,
        submenuItems: [
            { title: 'libray items and barrowd itms', href: '/reports' },
            { title: 'students with penalty', href: '/reports/penalty' },
            { title: 'transaction profile', href: '/reports/transaction-profile' },
            { title: 'missing collection', href: '/reports/missing-collection' },
            { title: 'core collection', href: '/reports/core-collection' }
        ],
    },
    {
        title: 'Clearance',
        href: '/clearance',
        icon: Folder,
    },
    {
        title: 'Statistical Data',
        href: '/statistical-data',
        icon: EthernetPortIcon,
        hasSubmenu: true,
        submenuItems: [
            { title: 'Comparative Report', href: '/reports' },
            { title: 'General Book Loan', href: '/reports/penalty' },
            { title: 'Graduate Book Loan', href: '/reports/transaction-profile' },

        ],

    },
    {
        title: 'User Logs',
        href: '/clearance',
        icon: Logs,
        hasSubmenu: true,
        submenuItems: [
            { title: 'Reports', href: '/reports' },
            { title: 'Patron Information', href: '/reports/penalty' },
            { title: 'Violation', href: '/reports/transaction-profile' },

        ],
    },
    {
        title: 'Inventory',
        href: '/clearance',
        icon: Boxes,
    },

];

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: route('home'),
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
];

const expandedMenus = ref<Set<string>>(new Set());

const toggleSubmenu = (title: string) => {
    if (expandedMenus.value.has(title)) {
        expandedMenus.value.delete(title);
    } else {
        expandedMenus.value.add(title);
    }
};
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton class="bg-background hover:bg-muted text-accent-foreground" size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
