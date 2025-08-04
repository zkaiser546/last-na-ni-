<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import UsersListTable from '@/components/UsersListTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

defineProps({
    users: Object,
});

</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">

            <h1 class="text-4xl">FUNCTIONALITY lang ha</h1>

            <div class="flex gap-2">
                <Link :href="route('users.import')">
                    <Button variant="secondary">Import Users</Button>
                </Link>
                <Link :href="route('admins.create')">
                    <Button variant="secondary">Add Staff Admin</Button>
                </Link>
                <Link :href="route('students.create')">
                    <Button variant="secondary">Add Student</Button>
                </Link>
                <Link :href="route('faculties.create')">
                    <Button variant="secondary">Add Faculty</Button>
                </Link>
                <Link :href="route('grad-students.create')">
                    <Button variant="secondary">Add Grad Student</Button>
                </Link>
            </div>

            <h1>List of all users</h1>
            <UsersListTable :users="users"/>
        </div>
    </AppLayout>
</template>
