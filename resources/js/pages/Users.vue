<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AppTabs from '@/components/AppTabs.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Tab data
const tabs = [
    { id: 'tab1', title: 'Users List', content: 'Users list content goes here' },
    { id: 'tab2', title: 'User Settings', content: 'User settings content goes here' },
    { id: 'tab3', title: 'User Reports', content: 'User reports content goes here' },
];
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">

            <!-- Replace the old tabs section with the component -->
            <AppTabs :tabs="tabs" />

            <AppearanceTabs />

        </div>
    </AppLayout>
</template>
