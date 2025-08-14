<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: '/reports' },
    { title: 'Transaction Profile', href: '/reports/transaction-profile' },
];

interface ProfileData {
    name: string;
    student_id: string;
    college: string;
    program: string;
    email: string;
    phone: string;
    profile_picture: string | null;
}

interface Transaction {
    date_borrowed: string;
    accession_number: string;
    title: string;
    due_date: string;
    date_returned: string | null;
}

const props = defineProps<{
    profileData: ProfileData | null;
    transactions: Transaction[];
}>();

const searchQuery = ref('');
const exportFormat = ref('CSV');

const handleSearch = () => {
    window.location.href = `/reports/transaction-profile?search=${searchQuery.value}`;
};
</script>

<template>
    <Head title="Transaction Profile" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div id="transactionProfile-content">
            <div class="container mx-auto px-4 py-8">
                <!-- Search Section -->
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
                            @keyup.enter="handleSearch"
                        />
                    </div>
                    <Button @click="handleSearch">
                        <i class="fas fa-search mr-2"></i>Search
                    </Button>
                    <!-- Download Button -->
                    <div class="flex items-center ml-auto gap-2">
                        <span class="text-sm text-gray-600">Select format:</span>
                        <select v-model="exportFormat" class="border border-gray-300 rounded px-3 py-2">
                            <option>CSV</option>
                            <option>Excel</option>
                            <option>PDF</option>
                        </select>
                        <Button variant="secondary" class="bg-usepgold text-usepmaroon hover:bg-yellow-500">
                            Download
                        </Button>
                    </div>
                </div>

                <!-- Profile Section - Only shown when data exists -->
                <div v-if="profileData" class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex flex-col md:flex-row items-start gap-6">
                        <!-- Profile Picture -->
                        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-4 border-usepmaroon">
                            <i v-if="!profileData.profile_picture" class="fas fa-user text-5xl text-gray-400"></i>
                            <img v-else :src="profileData.profile_picture" :alt="profileData.name" class="w-full h-full object-cover">
                        </div>

                        <!-- Profile Details -->
                        <div class="flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h2 class="text-xl font-bold text-usepmaroon">{{ profileData.name }}</h2>
                                    <p class="text-gray-600">Student ID: {{ profileData.student_id }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600"><i class="fas fa-university mr-2"></i>{{ profileData.college }}</p>
                                    <p class="text-gray-600"><i class="fas fa-book mr-2"></i>{{ profileData.program }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600"><i class="fas fa-envelope mr-2"></i>{{ profileData.email }}</p>
                                    <p class="text-gray-600"><i class="fas fa-phone mr-2"></i>{{ profileData.phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table - Only shown when profile exists -->
                <div v-if="profileData" class="bg-white rounded-lg shadow-lg p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Borrowed</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accession Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Returned</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="transaction in transactions" :key="transaction.accession_number">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ transaction.date_borrowed }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ transaction.accession_number }}</td>
                                    <td class="px-6 py-4">{{ transaction.title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ transaction.due_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ transaction.date_returned || '-' }}</td>
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

                <!-- Empty State Message -->
                <div v-if="!profileData" class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-search text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600">No Profile Found</h3>
                    <p class="text-gray-500">Search for a student using their Library/Student ID to view their transaction profile.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.input-focus:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(128, 0, 0, 0.2);
}
</style>
