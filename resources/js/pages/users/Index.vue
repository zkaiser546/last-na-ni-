<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

interface User {
    id: number
    first_name: string;
    last_name: string;
    email: string;
    user_type: string;
}

interface Props{
    users: User[];
}

const props = defineProps<Props>();

</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">

            <div class="flex gap-2">
                <Link :href="route('admins.create')">
                    <Button variant="secondary">Add Admin</Button>
                </Link>
            </div>

            <h1>List of all users</h1>
            <Table>
                <TableHeader>
                    <TableRow >
                        <TableHead >First Name</TableHead>
                        <TableHead >Last Name</TableHead>
                        <TableHead >Email</TableHead>
                        <TableHead >User Type</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="user in props.users" :key="user.id">
                        <TableCell >
                            {{ user.first_name }}
                        </TableCell>
                        <TableCell >
                            {{ user.last_name }}
                        </TableCell>
                        <TableCell >
                            {{ user.email }}
                        </TableCell>
                        <TableCell >
                            {{ user.user_type }}
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>
