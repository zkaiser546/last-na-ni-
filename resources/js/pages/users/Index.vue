<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

interface User {
    first_name: string;
    email: string;
    user_type?: string;
}

defineProps<{
    super_admin: User | null;
}>();

</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div>
                <Link :href="route('admins.create')">
                    <Button>Add Admin</Button>
                </Link>
            </div>
            <div v-if="super_admin" class="flex gap-4 super-admin-info">
                <h2>Super Admin</h2>
                <p><strong>Name:</strong> {{ super_admin.first_name }}</p>
                <p><strong>Email:</strong> {{ super_admin.email }}</p>
                <!-- Add other fields you want to display -->
            </div>
        </div>
    </AppLayout>
</template>
