<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Clearance', href: '/clearance' },
];

interface ValidationStatus {
    year: string;
    semester: string;
    status: string;
}

interface ClearanceRecord {
    date: string;
    reason: string;
    academicYear: string;
    semester: string;
    remarks: string;
}

const yearLevels = [
    '1st Year',
    '2nd Year',
    '3rd Year',
    '4th Year',
    '5th Year',
    '6th Year'
] as const;

const semesters = [
    '1st Semester',
    '2nd Semester',
    'Off Semester'
] as const;

const academicYears = [
    '2024-2025',
    '2025-2026',
    '2026-2027'
] as const;

// Update Props interface for better type safety
interface Props {
    data: {
        data: Array<{
            id: number;
            name: string;
            program: string;
        }>;
        current_page: number;
        per_page: number;
        last_page: number;
    };
}

const props = withDefaults(defineProps<Props>(), {
    data: () => ({
        data: [
            { id: 1, name: 'John Doe', program: 'BSIT' },
            { id: 2, name: 'Jane Smith', program: 'BSCS' },
            { id: 3, name: 'Mike Johnson', program: 'BSCE' },
            { id: 4, name: 'Sarah Wilson', program: 'BSA' },
            { id: 5, name: 'Tom Brown', program: 'BSME' }
        ],
        current_page: 1,
        per_page: 5,
        last_page: 1
    })
});

const filterInput = ref('');
const showValidationModal = ref(false);
const showClearanceModal = ref(false);
const selectedStudent = ref('');
const validationSchoolYear = ref('');
const clearanceRemarks = ref('');
const selectedYear = ref('');
const selectedSemester = ref('');

// Clearance specific states with proper typing
const clearanceRecords = ref<ClearanceRecord[]>([]);
const clearanceReason = ref('');
const selectedAcademicYear = ref('');
const selectedClearanceSemester = ref('');
const validationStatus = ref<ValidationStatus[]>([]);

const selectedExportFormat = ref('');

const onSearch = () => {
    router.get(route('clearance.index'), { search: filterInput.value }, { preserveState: true, preserveScroll: true });
};

const resetModals = () => {
    validationSchoolYear.value = '';
    clearanceRemarks.value = '';
    selectedStudent.value = '';
    showValidationModal.value = false;
    showClearanceModal.value = false;
    selectedYear.value = '';
    selectedSemester.value = '';
    clearanceReason.value = '';
    selectedAcademicYear.value = '';
    selectedClearanceSemester.value = '';
};

const openModal = (type: string, student: string) => {
    selectedStudent.value = student;
    if (type === 'validation') {
        showValidationModal.value = true;
    } else {
        showClearanceModal.value = true;
    }
};

const goToPage = (page: number) => {
    if (props.data?.current_page !== undefined) {
        router.get(route('clearance.index'), {
            page,
            search: filterInput.value
        }, {
            preserveState: true,
            preserveScroll: true
        });
    }
};

// Methods for validation with proper typing
const validateSelected = () => {
    if (!selectedYear.value || !selectedSemester.value) return;

    validationStatus.value.push({
        year: selectedYear.value,
        semester: selectedSemester.value,
        status: 'Validated'
    });
};

const addClearanceRecord = () => {
    if (!clearanceReason.value || !clearanceRemarks.value || !selectedAcademicYear.value || !selectedClearanceSemester.value) return;

    clearanceRecords.value.push({
        date: new Date().toLocaleDateString(),
        reason: clearanceReason.value,
        academicYear: selectedAcademicYear.value,
        semester: selectedClearanceSemester.value,
        remarks: clearanceRemarks.value
    });

    // Reset form
    clearanceReason.value = '';
    clearanceRemarks.value = '';
};

const handleExport = () => {
    if (!selectedExportFormat.value) return;

    router.post(route('clearance.export'), {
        format: selectedExportFormat.value,
        student: selectedStudent.value
    }, {
        preserveState: true,
        onSuccess: (response) => {
            // Trigger download using the returned URL
            window.open(response.url, '_blank');
            selectedExportFormat.value = '';
        },
    });
};
</script>

<template>
    <Head title="Clearance" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-8">
            <!-- Search -->
            <div class="flex gap-2 mb-4">
                <Input v-model="filterInput" placeholder="Search student name..." class="w-[320px]" @keyup.enter="onSearch" />
                <Button @click="onSearch">Search</Button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg border bg-white shadow">
                <table class="w-full text-sm text-center">
                    <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase">
                        <th class="py-3 px-6 text-center">Student Name</th>
                        <th class="py-3 px-6 text-center">Program</th>
                        <th class="py-3 px-6 text-center">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="student in props.data.data" :key="student.name" class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 px-6">{{ student.name }}</td>
                        <td class="py-3 px-6">{{ student.program }}</td>
                        <td class="py-3 px-6">
                            <div class="flex gap-2 justify-center">
                                <Button size="sm" @click="openModal('validation', student.name)">Validation</Button>
                                <Button size="sm" @click="openModal('clearance', student.name)">Clearance</Button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Main Table Pagination -->
            <div class="flex justify-end items-center gap-2 mt-4 mb-4">
                <Button :disabled="props.data.current_page === 1" @click="goToPage(1)" size="sm">«</Button>
                <Button :disabled="props.data.current_page === 1" @click="goToPage(props.data.current_page - 1)" size="sm">‹</Button>
                <span class="px-4">Page {{ props.data.current_page }} of {{ props.data.last_page }}</span>
                <Button :disabled="props.data.current_page === props.data.last_page" @click="goToPage(props.data.current_page + 1)" size="sm">›</Button>
                <Button :disabled="props.data.current_page === props.data.last_page" @click="goToPage(props.data.last_page)" size="sm">»</Button>
            </div>
        </div>

        <!-- Validation Modal -->
        <div v-if="showValidationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl relative overflow-hidden">
                <button class="absolute top-2 right-2 text-gray-400 hover:text-red-600" @click="resetModals">
                    <span class="sr-only">Close</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
                <div class="p-4 font-bold text-lg border-b">Validation for {{ selectedStudent }}</div>

                <!-- Validation Status Table -->
                <div class="p-4">
                    <table class="w-full text-sm border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border text-left">Year Level</th>
                                <th class="py-2 px-4 border">1st Semester</th>
                                <th class="py-2 px-4 border">2nd Semester</th>
                                <th class="py-2 px-4 border">Off Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="year in yearLevels" :key="year" class="border">
                                <td class="py-2 px-4 border text-left">{{ year }}</td>
                                <td v-for="sem in ['1st Semester', '2nd Semester', 'Off Semester']"
                                    :key="sem"
                                    class="py-2 px-4 border text-center">
                                    <span v-if="validationStatus.find(v => v.year === year && v.semester === sem)"
                                          class="text-green-600 font-medium">
                                        ✓ Validated
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Validation Controls -->
                <div class="p-4 bg-gray-50 space-y-4 rounded-b-2xl">
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block font-medium mb-1">Select Year Level</label>
                            <select v-model="selectedYear"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2">
                                <option value="">Select Year</option>
                                <option v-for="year in yearLevels" :key="year" :value="year">
                                    {{ year }}
                                </option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block font-medium mb-1">Select Semester</label>
                            <select v-model="selectedSemester"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2">
                                <option value="">Select Semester</option>
                                <option v-for="sem in semesters" :key="sem" :value="sem">
                                    {{ sem }}
                                </option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <Button @click="validateSelected"
                                    :disabled="!selectedYear || !selectedSemester"
                                    class="bg-usepmaroon text-white hover:bg-usepmaroon/90">
                                Validate
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clearance Modal -->
        <div v-if="showClearanceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl relative overflow-hidden">
                <button class="absolute top-2 right-2 text-gray-400 hover:text-red-600" @click="resetModals">
                    <span class="sr-only">Close</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <!-- Header -->
                <div class="p-4 border-b">
                    <h2 class="font-bold text-lg">Clearance for {{ selectedStudent }}</h2>
                </div>

                <!-- Search and Export -->
                <div class="p-4 flex justify-between items-center">
                    <div class="flex gap-2">
                        <Input placeholder="Search records..." class="w-[320px]" />
                        <Button>Search</Button>
                    </div>
                    <div class="flex gap-2">
                        <select v-model="selectedExportFormat"
                                class="rounded-md border border-gray-300 px-3 py-2">
                            <option value="">Export as...</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                        <Button @click="handleExport"
                               :disabled="!selectedExportFormat">
                            Export
                        </Button>
                    </div>
                </div>

                <!-- Records Table -->
                <div class="p-4 pt-0">
                    <div class="border rounded-lg overflow-hidden">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 uppercase">
                                    <th class="py-3 px-4 border-b text-left">Date</th>
                                    <th class="py-3 px-4 border-b text-left">Reason</th>
                                    <th class="py-3 px-4 border-b text-left">Academic Year</th>
                                    <th class="py-3 px-4 border-b text-left">Semester</th>
                                    <th class="py-3 px-4 border-b text-left">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="record in clearanceRecords" :key="record.date" class="border-b">
                                    <td class="py-2 px-4 text-left">{{ record.date }}</td>
                                    <td class="py-2 px-4 text-left">{{ record.reason }}</td>
                                    <td class="py-2 px-4 text-left">{{ record.academicYear }}</td>
                                    <td class="py-2 px-4 text-left">{{ record.semester }}</td>
                                    <td class="py-2 px-4 text-left">{{ record.remarks }}</td>
                                </tr>
                                <tr v-if="clearanceRecords.length === 0">
                                    <td colspan="5" class="py-4 text-center text-gray-500">No records found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-end items-center gap-2 mt-4">
                        <Button :disabled="props.data.current_page === 1" @click="goToPage(1)" size="sm">«</Button>
                        <Button :disabled="props.data.current_page === 1" @click="goToPage(props.data.current_page - 1)" size="sm">‹</Button>
                        <span>Page {{ props.data.current_page }} of {{ props.data.last_page }}</span>
                        <Button :disabled="props.data.current_page === props.data.last_page" @click="goToPage(props.data.current_page + 1)" size="sm">›</Button>
                        <Button :disabled="props.data.current_page === props.data.last_page" @click="goToPage(props.data.last_page)" size="sm">»</Button>
                    </div>
                </div>

                <!-- Input Form -->
                <div class="p-4 bg-gray-50 space-y-4 rounded-b-2xl">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block font-medium mb-1">Academic Year</label>
                            <select v-model="selectedAcademicYear"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2">
                                <option value="">Select A.Y.</option>
                                <option v-for="year in academicYears" :key="year" :value="year">
                                    {{ year }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Semester</label>
                            <select v-model="selectedClearanceSemester"
                                    class="w-full rounded-md border border-gray-300 px-3 py-2">
                                <option value="">Select Semester</option>
                                <option v-for="sem in semesters" :key="sem" :value="sem">
                                    {{ sem }}
                                </option>
                            </select>
                        </div>
                        <div class="col-span-3">
                            <label class="block font-medium mb-1">Reason</label>
                            <Input v-model="clearanceReason" placeholder="Enter reason..." class="w-full" />
                        </div>
                        <div class="col-span-3">
                            <label class="block font-medium mb-1">Remarks</label>
                            <Input v-model="clearanceRemarks" placeholder="Enter remarks..." class="w-full" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <Button @click="addClearanceRecord"
                                :disabled="!clearanceReason || !clearanceRemarks || !selectedAcademicYear || !selectedClearanceSemester"
                                class="bg-usepmaroon text-white hover:bg-usepmaroon/90">
                            Verify
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
th, td {
    border-bottom: 1px solid #eee;
}
</style>
