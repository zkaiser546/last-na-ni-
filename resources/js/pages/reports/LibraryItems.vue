<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: '/reports' },
    { title: 'Library Items', href: '/reports/library-items' },
];
</script>

<template>
    <Head title="Library Items Report" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-8">
            <!-- Filters -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-2 w-full">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Year</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-2">
                            <option>2025</option>
                            <option>2024</option>
                            <option>2023</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Year</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-2">
                            <option>2025</option>
                            <option>2024</option>
                            <option>2023</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-2">
                            <option>All</option>
                            <option>Main Library</option>
                            <option>Branch 1</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-2">
                            <option>All</option>
                            <option>Books</option>
                            <option>Electronic</option>
                            <option>Periodical</option>
                            <option>Thesis</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-2">
                            <option>All</option>
                            <option>Available</option>
                            <option>Unavailable</option>
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
                    <Input placeholder="Search..." class="pl-10" />
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

            <!-- Tabs -->
            <div class="mb-4 border-b">
                <div class="flex -mb-px">
                    <button class="px-4 py-2 border-b-2 border-usepmaroon text-usepmaroon">Books</button>
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
                            <td class="px-6 py-4">ACC-001</td>
                            <td class="px-6 py-4">CN-001</td>
                            <td class="px-6 py-4">Introduction to Computer Science</td>
                            <td class="px-6 py-4">John Doe</td>
                            <td class="px-6 py-4">Computer Science Basics</td>
                            <td class="px-6 py-4">QA76</td>
                            <td class="px-6 py-4">New York</td>
                            <td class="px-6 py-4">Tech Publishers</td>
                            <td class="px-6 py-4">2022</td>
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
