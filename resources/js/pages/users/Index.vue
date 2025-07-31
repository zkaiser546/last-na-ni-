<script setup lang="ts" generic="TData, TValue">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import DataTable from '@/components/user/data-table.vue';
import { onMounted, ref } from 'vue';
import type { Payment } from '@/components/user/columns'
import { columns } from '@/components/user/columns'

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

const data = ref<Payment[]>([])

async function getData(): Promise<Payment[]> {
    // Fetch data from your API here.
    return [
        {
            id: '728ed52f',
            amount: 100,
            status: 'pending',
            email: 'm@example.com',
        },
        {
            id: '728e534d52f',
            amount: 1300,
            status: 'pending',
            email: 'm@example.com',
        },
    ]
}

onMounted(async () => {
    data.value = await getData()
})

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
            <DataTable :columns="columns" :data="data" />
        </div>
    </AppLayout>
</template>
