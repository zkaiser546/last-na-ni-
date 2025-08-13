<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { onMounted } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    userCount: Number,
    bookCount: Number,
    borrowedBooksCount: Number,
    monthlyUsersBorrow: Number,
    monthlyTrends: Array,
    yearlyTrends: Array,
    selectedYear: Number,
    selectedMonth: Number,
    bookPercentChange: Number,
    userPercentChange: Number,
    borrowedBooksPercentChange: Number,
    monthlyUsersBorrowPercentChange: Number,
    topPrograms: Array,
});

const year = ref(props.selectedYear || new Date().getFullYear());
const month = ref(props.selectedMonth || 1); // Preset to January
const chartType = ref('monthly');

const years = [2025, 2024, 2023];
const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

function updateDashboard() {
    router.get('/dashboard', { year: year.value, month: month.value }, { preserveState: true, replace: true });
}

watch(year, updateDashboard);
watch(month, updateDashboard);

let chartInstance = null;

function renderChart() {
    const ctx = document.getElementById('borrowingChart');
    if (!ctx) return;
    if (chartInstance) chartInstance.destroy();
    const data = chartType.value === 'monthly' ? props.monthlyTrends : props.yearlyTrends;
    const labels = chartType.value === 'monthly' ? months : years.map(String);
    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Borrowings',
                data,
                backgroundColor: 'rgba(153, 27, 27, 0.7)',
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
        },
    });
}

let topProgramsChartInstance = null;
function renderTopProgramsChart() {
    const ctx = document.getElementById('topProgramsChart');
    if (!ctx) return;
    if (topProgramsChartInstance) topProgramsChartInstance.destroy();
    const labels = props.topPrograms.map(p => p.name);
    const data = props.topPrograms.map(p => p.count);
    const colors = data.map((_, idx) => idx === 0 ? 'rgba(255, 193, 7, 0.8)' : 'rgba(37, 99, 235, 0.7)'); // Gold for top, blue for others
    topProgramsChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Borrowings',
                data,
                backgroundColor: colors,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
        },
    });
}

watch(chartType, renderChart);
onMounted(() => {
    renderChart();
    renderTopProgramsChart();
});
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout>
        <div id="content-container" class="h-full bg-white border border-gray-200 rounded-lg shadow-sm p-8 space-y-6">
            <div id="dashboard-content">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-usepmaroon">Library Dashboard</h1>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <select v-model="year" id="year-selector" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:ring-2 focus:ring-usepmaroon focus:border-usepmaroon">
                                <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold text-usepmaroon">{{ bookCount }}</div>
                                <div class="text-gray-500">Total Books</div>
                            </div>
                            <div class="bg-usepgold/20 p-4 rounded-full">
                                <i class="fa-solid fa-book text-2xl text-usepgold"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-4">
                            <span :class="{'text-green-500': props.bookPercentChange > 0, 'text-red-500': props.bookPercentChange < 0, 'text-gray-500': props.bookPercentChange === 0}" class="font-medium">
                                {{ props.bookPercentChange }}%
                            </span> from last year
                        </p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold text-usepmaroon">{{ userCount }}</div>
                                <div class="text-gray-500">Total Users</div>
                            </div>
                            <div class="bg-usepgold/20 p-4 rounded-full">
                                <i class="fa-solid fa-users text-2xl text-usepgold"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-4">
                            <span :class="{'text-green-500': props.userPercentChange > 0, 'text-red-500': props.userPercentChange < 0, 'text-gray-500': props.userPercentChange === 0}" class="font-medium">
                                {{ props.userPercentChange }}%
                            </span> from last year
                        </p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold text-usepmaroon">{{ borrowedBooksCount }}</div>
                                <div class="text-gray-500">Borrowed Books</div>
                            </div>
                            <div class="bg-usepgold/20 p-4 rounded-full">
                                <i class="fa-solid fa-book-open-reader text-2xl text-usepgold"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-4">
                            <span :class="{'text-green-500': props.borrowedBooksPercentChange > 0, 'text-red-500': props.borrowedBooksPercentChange < 0, 'text-gray-500': props.borrowedBooksPercentChange === 0}" class="font-medium">
                                {{ props.borrowedBooksPercentChange }}%
                            </span> from last year
                        </p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold text-usepmaroon">{{ monthlyUsersBorrow ?? 0 }}</div>
                                <div class="text-gray-500">Monthly Users Borrow</div>
                            </div>
                            <div class="bg-usepgold/20 p-4 rounded-full">
                                <i class="fa-solid fa-calendar-days text-2xl text-usepgold"></i>
                            </div>
                        </div>
                        <div class="relative mt-2">
                            <select v-model="month" id="month-selector" class="appearance-none w-full bg-white border border-gray-300 rounded-lg px-3 py-1 pr-8 text-sm focus:ring-2 focus:ring-usepmaroon focus:border-usepmaroon">
                                <option :value="null">Select Month</option>
                                <option v-for="(m, idx) in months" :key="idx" :value="idx+1">{{ m }}</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-4">
                            <span :class="{'text-green-500': props.monthlyUsersBorrowPercentChange > 0, 'text-red-500': props.monthlyUsersBorrowPercentChange < 0, 'text-gray-500': props.monthlyUsersBorrowPercentChange === 0}" class="font-medium">
                                {{ props.monthlyUsersBorrowPercentChange }}%
                            </span> from last year
                        </p>
                    </div>
                </div>
                <!-- Borrowing Trends Chart -->
                <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">Borrowing Trends</h3>
                        <div class="flex gap-2">
                            <button @click="chartType = 'monthly'" :class="['px-3 py-1 text-sm rounded-lg', chartType === 'monthly' ? 'bg-usepmaroon text-white' : 'bg-gray-100 text-gray-600']">Monthly</button>
                            <button @click="chartType = 'yearly'" :class="['px-3 py-1 text-sm rounded-lg', chartType === 'yearly' ? 'bg-usepmaroon text-white' : 'bg-gray-100 text-gray-600']">Yearly</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="borrowingChart"></canvas>
                    </div>
                </div>
                <!-- Top Programs Bar Chart -->
                <div class="bg-white border border-gray-200 rounded-lg shadow p-6 mt-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-blue-700">Top Programs</h3>
                        <span class="text-sm text-gray-500">Borrowing Top Programs</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="topProgramsChart"></canvas>
                    </div>
                    <!-- List of all school programs below the chart -->
                    <div class="mt-6">
                        <h4 class="text-md font-semibold text-gray-700 mb-2">USEP Programs</h4>
                        <div class="grid grid-cols-1 md:grid-cols-7 gap-2 text-sm text-gray-600">
                            <div class="flex flex-col items-center">
                                <span>BSED BSABE</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span>BSED MATH</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span>BSED ENG</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span>BSED FIL</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span>BSNED</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span>BSABE</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span>Other USEP Programs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
