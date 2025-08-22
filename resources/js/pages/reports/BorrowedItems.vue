<template>
    <Head title="Borrowed Items Report" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-8">
            <!-- Filters -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <Input type="date" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <Input type="date" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Filter</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-2">
                            <option>All</option>
                            <option>Books</option>
                            <option>Electronic Resources</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-end">
                    <Button class="bg-usepmaroon text-white hover:bg-red-900">Generate Report</Button>
                </div>
            </div>

            <!-- Search and Export -->
            <div class="flex flex-wrap items-center gap-2 md:gap-4 pb-4">
                <div class="relative w-full md:w-96">
                    <Input placeholder="Search Library/Student ID..." class="pl-10" />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                <Button>Search</Button>
                <div class="flex items-center ml-auto gap-2">
                    <span class="text-sm text-gray-600">Select format:</span>
                    <select class="border border-gray-300 rounded px-3 py-2">
                        <option>CSV</option>
                    </select>
                    <Button variant="secondary">Download</Button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg border">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Accession Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Borrowed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Returned</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff Incharge</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Borrower's Name</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4">ACC-001</td>
                        <td class="px-6 py-4">Introduction to Computer Science</td>
                        <td class="px-6 py-4">Book</td>
                        <td class="px-6 py-4">2023-05-15</td>
                        <td class="px-6 py-4">2023-06-01</td>
                        <td class="px-6 py-4">Ms. Rodriguez</td>
                        <td class="px-6 py-4">Juan Dela Cruz</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-4">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of <span class="font-medium">12</span> entries
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" disabled>Previous</Button>
                    <Button variant="outline" class="bg-usepmaroon text-white">1</Button>
                    <Button variant="outline">2</Button>
                    <Button variant="outline">3</Button>
                    <Button variant="outline">Next</Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: '/reports' },
    { title: 'Borrowed Items', href: '/reports/borrowed-items' },
];
</script>
