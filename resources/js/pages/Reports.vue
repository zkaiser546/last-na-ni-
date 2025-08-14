<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: '/reports' },
];

const activeTab = ref('libray');
</script>

<template>
    <Head title="Reports" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-8">
            <!-- Main Tabs -->
            <div class="mb-6">
                <div class="flex border-b">
                    <button
                        @click="activeTab = 'libray'"
                        :class="['px-4 py-2 -mb-px',
                                activeTab === 'libray'
                                ? 'text-usepmaroon border-b-2 border-usepmaroon font-medium'
                                : 'text-gray-500 hover:text-usepmaroon']"
                    >
                        libray items
                    </button>
                    <button
                        @click="activeTab = 'barrowd'"
                        :class="['px-4 py-2 -mb-px',
                                activeTab === 'barrowd'
                                ? 'text-usepmaroon border-b-2 border-usepmaroon font-medium'
                                : 'text-gray-500 hover:text-usepmaroon']"
                    >
                        barrowd itms
                    </button>
                </div>
            </div>

            <!-- Library Items Content -->
            <div v-if="activeTab === 'libray'" class="space-y-6">
                <!-- Filters -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-2 w-full">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Year</label>
                            <select class="w-full border border-gray-300 rounded px-3 py-2">
                                <option>2025</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Year</label>
                            <select class="w-full border border-gray-300 rounded px-3 py-2">
                                <option>2025</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <select class="w-full border border-gray-300 rounded px-3 py-2">
                                <option>All</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select class="w-full border border-gray-300 rounded px-3 py-2">
                                <option>All</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select class="w-full border border-gray-300 rounded px-3 py-2">
                                <option>All</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-end h-full pt-5">
                        <Button class="bg-usepmaroon text-white hover:bg-red-900">Generate Report</Button>
                    </div>
                </div>

                <!-- Search and Export -->
                <div class="flex flex-wrap items-center gap-2 md:gap-4">
                    <div class="relative w-full md:w-96">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <Input type="text" class="w-full pl-10" placeholder="Search..." />
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

                <!-- Category Tabs -->
                <div class="border-b">
                    <div class="flex -mb-px">
                        <button class="px-4 py-2 text-usepmaroon border-b-2 border-usepmaroon">Books</button>
                        <button class="px-4 py-2 text-gray-500 hover:text-usepmaroon">Electronic</button>
                        <button class="px-4 py-2 text-gray-500 hover:text-usepmaroon">Periodical</button>
                        <button class="px-4 py-2 text-gray-500 hover:text-usepmaroon">Thesis</button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-lg border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Accession Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Call Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Book Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classification</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Place of Publication</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publisher</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Copyright Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4">E-001</td>
                                <td class="px-6 py-4">ECN-001</td>
                                <td class="px-6 py-4">Digital Systems</td>
                                <td class="px-6 py-4">Robert Johnson</td>
                                <td class="px-6 py-4">Electronics Series</td>
                                <td class="px-6 py-4">TK7868</td>
                                <td class="px-6 py-4">Boston</td>
                                <td class="px-6 py-4">Digital Press</td>
                                <td class="px-6 py-4">2020</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Borrowed Items Content -->
            <div v-if="activeTab === 'barrowd'" class="space-y-6">
                <!-- Filters -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <Input type="date" class="w-full" placeholder="mm/dd/yyyy" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Date</label>
                            <Input type="date" class="w-full" placeholder="mm/dd/yyyy" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Filter</label>
                            <select class="w-full border border-gray-300 rounded px-3 py-2">
                                <option>All</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-end h-full pt-5">
                        <Button class="bg-usepmaroon text-white hover:bg-red-900">Generate Report</Button>
                    </div>
                </div>

                <!-- Search and Export -->
                <div class="flex flex-wrap items-center gap-2 md:gap-4">
                    <div class="relative w-full md:w-96">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <Input type="text" class="w-full pl-10" placeholder="Search Library/Student ID..." />
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
                            <tr>
                                <td class="px-6 py-4">ACC-002</td>
                                <td class="px-6 py-4">Advanced Programming</td>
                                <td class="px-6 py-4">E-Book</td>
                                <td class="px-6 py-4">2023-05-20</td>
                                <td class="px-6 py-4">-</td>
                                <td class="px-6 py-4">Mr. Smith</td>
                                <td class="px-6 py-4">Maria Santos</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
