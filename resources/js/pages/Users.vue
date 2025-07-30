<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Tab state
const activeTab = ref('tab1');

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
            <!-- put content here -->
            <div class="flex border-b border-gray-200">
                <button
                    v-for="tab in tabs"
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    :class="[
                        'px-4 py-2 -mb-px border-b-2 font-medium',
                        activeTab === tab.id
                            ? 'border-blue-500 text-blue-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    ]"
                >
                    {{ tab.title }}
                </button>
            </div>

            <!-- Tab content -->
            <div class="mt-4">
                <div v-for="tab in tabs" :key="tab.id" v-show="activeTab === tab.id">
                    {{ tab.content }}
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
    /* Optional: Add smooth transition for tab content */
    div[v-show] {
        transition: opacity 0.2s ease-in-out;
    }
</style>
