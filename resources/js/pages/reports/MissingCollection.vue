<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: '/reports' },
    { title: 'Missing Collection', href: '/reports/missing-collection' },
];

// Props interface
interface Props {
    bookData: Array<{
        accession_number: string;
        call_number: string;
        author: string;
        title: string;
        program: string;
        location: string;
    }>;
    electronicData: Array<{
        accession_number: string;
        author: string;
        title: string;
    }>;
    periodicalData: Array<{
        accession_number: string;
        author: string;
        title: string;
    }>;
    thesisData: Array<{
        accession_number: string;
        author: string;
        title: string;
    }>;
}

const props = defineProps<Props>();

// Active tab state
const activeTab = ref('book');
const selectedYear = ref('2023');
const searchQuery = ref('');
const exportFormat = ref('CSV');

// Function to get current data based on active tab
const getCurrentData = () => {
    switch (activeTab.value) {
        case 'book':
            return props.bookData;
        case 'electronic':
            return props.electronicData;
        case 'periodical':
            return props.periodicalData;
        case 'thesis':
            return props.thesisData;
        default:
            return [];
    }
};
</script>

<template>
    <Head title="Missing Collection" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-8">
            <!-- Year Selection -->
            <div class="flex items-center gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year:</label>
                    <select v-model="selectedYear" class="border border-gray-300 rounded-md px-3 py-2">
                        <option>2023</option>
                        <option>2024</option>
                        <option>2025</option>
                    </select>
                </div>
            </div>

            <!-- Search and Export -->
            <div class="flex flex-wrap items-center gap-2 md:gap-4 pb-4">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <Input
                        v-model="searchQuery"
                        type="text"
                        class="w-full pl-10"
                        placeholder="Search..."
                    />
                </div>
                <Button>Search</Button>
                <div class="flex items-center ml-auto gap-2">
                    <span class="text-sm text-gray-600">Select format:</span>
                    <select v-model="exportFormat" class="border border-gray-300 rounded px-3 py-2">
                        <option>CSV</option>
                    </select>
                    <Button variant="secondary">Download</Button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-6 border-b">
                <div class="flex -mb-px">
                    <button
                        @click="activeTab = 'book'"
                        :class="['px-4 py-2', activeTab === 'book' ? 'border-b-2 border-usepmaroon text-usepmaroon font-medium' : 'text-gray-500 hover:text-usepmaroon']"
                    >
                        Book
                    </button>
                    <button
                        @click="activeTab = 'electronic'"
                        :class="['px-4 py-2', activeTab === 'electronic' ? 'border-b-2 border-usepmaroon text-usepmaroon font-medium' : 'text-gray-500 hover:text-usepmaroon']"
                    >
                        Electronic Collection
                    </button>
                    <button
                        @click="activeTab = 'periodical'"
                        :class="['px-4 py-2', activeTab === 'periodical' ? 'border-b-2 border-usepmaroon text-usepmaroon font-medium' : 'text-gray-500 hover:text-usepmaroon']"
                    >
                        Periodical Magazine
                    </button>
                    <button
                        @click="activeTab = 'thesis'"
                        :class="['px-4 py-2', activeTab === 'thesis' ? 'border-b-2 border-usepmaroon text-usepmaroon font-medium' : 'text-gray-500 hover:text-usepmaroon']"
                    >
                        Thesis Dissertation
                    </button>
                </div>
            </div>

            <!-- Book Table -->
            <div v-if="activeTab === 'book'" class="overflow-x-auto rounded-lg border">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Accession Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Call Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title of the Book</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in bookData" :key="item.accession_number">
                            <td class="px-6 py-4">{{ item.accession_number }}</td>
                            <td class="px-6 py-4">{{ item.call_number }}</td>
                            <td class="px-6 py-4">{{ item.author }}</td>
                            <td class="px-6 py-4">{{ item.title }}</td>
                            <td class="px-6 py-4">{{ item.program }}</td>
                            <td class="px-6 py-4">{{ item.location }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Electronic Collection Table -->
            <div v-if="activeTab === 'electronic'" class="overflow-x-auto rounded-lg border">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Accession Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in electronicData" :key="item.accession_number">
                            <td class="px-6 py-4">{{ item.accession_number }}</td>
                            <td class="px-6 py-4">{{ item.author }}</td>
                            <td class="px-6 py-4">{{ item.title }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Periodical Magazine Table -->
            <div v-if="activeTab === 'periodical'" class="overflow-x-auto rounded-lg border">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Accession Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in periodicalData" :key="item.accession_number">
                            <td class="px-6 py-4">{{ item.accession_number }}</td>
                            <td class="px-6 py-4">{{ item.author }}</td>
                            <td class="px-6 py-4">{{ item.title }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Thesis Dissertation Table -->
            <div v-if="activeTab === 'thesis'" class="overflow-x-auto rounded-lg border">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Accession Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in thesisData" :key="item.accession_number">
                            <td class="px-6 py-4">{{ item.accession_number }}</td>
                            <td class="px-6 py-4">{{ item.author }}</td>
                            <td class="px-6 py-4">{{ item.title }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-gray-600">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">12</span> entries
                </div>
                <div class="flex space-x-2">
                    <Button variant="outline">Previous</Button>
                    <Button variant="outline" class="bg-usepmaroon text-white">1</Button>
                    <Button variant="outline">2</Button>
                    <Button variant="outline">3</Button>
                    <Button variant="outline">Next</Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
