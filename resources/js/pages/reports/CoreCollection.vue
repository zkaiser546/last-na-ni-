<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';
import { ref, computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: '/reports' },
    { title: 'Core Collection', href: '/reports/core-collection' },
];

const props = defineProps<{
    years: string[];
    data: {
        data: Array<{
            classification: string;
            titles: number;
            volumes: number;
            copyright: Array<{
                year: string;
                titles: number;
                volumes: number;
            }>;
        }>;
        current_page: number;
        per_page: number;
        last_page: number;
    };
}>();

const selectedYear = ref(props.years[0]);
const searchQuery = ref('');
const exportFormat = ref('CSV');

// Methods for search and export functionality
const handleSearch = () => {
    // Search implementation
};

const handleExport = () => {
    // Export implementation
};

const hasData = computed(() => {
    return props.data.data.length > 0 && props.data.data.some(item => {
        const copyrightData = item.copyright.find(c => c.year === selectedYear);
        return (copyrightData?.titles || 0) > 0 || (copyrightData?.volumes || 0) > 0;
    });
});
</script>

<template>
    <Head title="Core Collection" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6 pb-16">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 space-y-6">
                    <!-- Year Selection -->
                    <div class="flex items-center gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year:</label>
                            <select v-model="selectedYear" class="border border-gray-300 rounded-md px-3 py-2">
                                <option v-for="year in props.years" :key="year" :value="year">{{ year }} years back</option>
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
                            <Button variant="secondary" :disabled="!hasData">Download</Button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto rounded-lg border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3"></th>
                                    <th class="px-6 py-3"></th>
                                    <th class="px-6 py-3"></th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase border-b" colspan="2">Copyright</th>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classification</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total No. of Titles</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total No. of Volumes</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">No. of Titles</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">No. of Volumes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="item in props.data.data" :key="item.classification">
                                    <td class="px-6 py-4">{{ item.classification }}</td>
                                    <td class="px-6 py-4 text-center">{{ item.titles }}</td>
                                    <td class="px-6 py-4 text-center">{{ item.volumes }}</td>
                                    <td class="px-6 py-4 text-center">
                                        {{ item.copyright.find(c => c.year === selectedYear)?.titles || 0 }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ item.copyright.find(c => c.year === selectedYear)?.volumes || 0 }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-6">
                        <div class="text-sm text-gray-600">
                            Showing <span class="font-medium">{{ ((props.data.current_page - 1) * props.data.per_page) + 1 }}</span> to
                            <span class="font-medium">{{ Math.min(props.data.current_page * props.data.per_page, props.data.data.length) }}</span> of
                            <span class="font-medium">{{ props.data.data.length }}</span> entries
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
            </div>
        </div>
    </AppLayout>
</template>
