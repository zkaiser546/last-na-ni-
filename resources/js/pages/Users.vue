<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AppTabs from '@/components/AppTabs.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import UsersList from '@/components/user/UsersList.vue';
import AddUser from '@/components/user/AddUser.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const tabs = [
    { id: 'tab1', title: 'Users List' },
    { id: 'tab2', title: 'Add User' },
    { id: 'tab3', title: 'User Reports' },
];

</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">

            <AppTabs :tabs="tabs">
                <!-- Tab 1 content with HTML -->
                <template #tab1>
                    <div class="space-y-4">
                        <h2 class="font-bold">Users Management</h2>
                        <div class="text-sm grid grid-cols-1 md:grid-cols-2 gap-4">
                            <UsersList />
                        </div>
                    </div>
                </template>

                <!-- Tab 2 content with HTML -->
                <template #tab2>
                    <form class="space-y-4">
                        <h2 class="font-bold">Users Management</h2>
                        <div class="text-sm grid grid-cols-1 md:grid-cols-2 gap-4">
                            <AddUser />
                        </div>
                    </form>
                </template>

                <!-- Tab 3 content with HTML -->
                <template #tab3>
                    <div>
                        <h2 class="text-xl font-bold mb-4">User Reports</h2>
                        <table class="w-full border-collapse border">
                            <thead>
                            <tr class="bg-gray-50">
                                <th class="border p-2 text-left">Report</th>
                                <th class="border p-2 text-left">Date</th>
                                <th class="border p-2 text-left">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="border p-2">Monthly Active Users</td>
                                <td class="border p-2">2024-01-15</td>
                                <td class="border p-2"><span class="text-green-600">Complete</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </template>
            </AppTabs>
        </div>
    </AppLayout>
</template>
