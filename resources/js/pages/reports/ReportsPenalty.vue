<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: '/reports' },
    { title: 'Students with Penalty', href: '/reports/penalty' },
];

interface PenaltyData {
    student_id: string;
    name: string;
    penalty_type: string;
    amount: number;
    status: string;
    date: string;
}

const props = defineProps<{
    penaltyData: PenaltyData[];
}>();

const filterType = ref('All');
const searchQuery = ref('');
const exportFormat = ref('CSV');
</script>

<template>
    <Head title="Students with Penalty" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-8 space-y-6">
            <h2 class="text-2xl font-bold">Students with Penalty</h2>

            <!-- Search and Filters -->
            <div class="flex flex-wrap items-center gap-2 md:gap-4 pb-4">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <Input
                        v-model="searchQuery"
                        type="text"
                        class="w-full pl-10"
                        placeholder="Search Library/Student ID..."
                    />
                </div>
                <Button>Search</Button>

                <!-- Export Controls -->
                <div class="flex items-center ml-auto gap-2">
                    <span class="text-sm text-gray-600">Select format:</span>
                    <select v-model="exportFormat" class="border border-gray-300 rounded px-3 py-2">
                        <option>CSV</option>
                    </select>
                    <Button variant="secondary">Download</Button>
                </div>
            </div>

            <!-- Filter -->
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium">Filter:</span>
                <select v-model="filterType" class="border border-gray-300 rounded px-3 py-2">
                    <option>All</option>
                    <option>Late Return</option>
                    <option>Damaged Book</option>
                    <option>Lost Book</option>
                </select>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg border">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penalty Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in penaltyData" :key="item.student_id">
                            <td class="px-6 py-4">{{ item.student_id }}</td>
                            <td class="px-6 py-4">{{ item.name }}</td>
                            <td class="px-6 py-4">{{ item.penalty_type }}</td>
                            <td class="px-6 py-4">${{ item.amount.toFixed(2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs"
                                      :class="item.status === 'Unpaid' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ item.date }}</td>
                            <td class="px-6 py-4">
                                <Button size="sm" variant="outline">Pay</Button>
                            </td>
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
