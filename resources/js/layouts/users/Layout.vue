<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Staff Admins',
        href: '/users/admins',
    },
    {
        title: 'Import',
        href: '/users/import',
    },
];

const page = usePage();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="p-4">
        <div class="flex flex-col space-y-2 ">
            <!-- Header Navigation -->
            <header class="w-full">
                <nav class="flex flex-wrap items-center gap-1">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="ghost"
                        :class="['justify-start', { 'bg-muted': currentPath.startsWith(item.href) }]"
                        as-child
                    >
                        <Link :href="item.href">
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </header>

            <Separator />

            <!-- Main Content Area -->
            <main class="flex justify-center">
                <section class="w-full">
                    <slot />
                </section>
            </main>
        </div>
    </div>
</template>
