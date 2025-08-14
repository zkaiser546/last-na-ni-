<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import { AlertCircle, X } from 'lucide-vue-next'
import { ref, onMounted } from 'vue'
import WelcomeBookDialog from '@/components/WelcomeBookDialog.vue';
import WelcomeSearch from '@/components/WelcomeSearch.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

// for alert
const page = usePage()
const name = page.props.name;
const showAlert = ref(true)
onMounted(() => {
    if (page.props.flash.error) {
        setTimeout(() => {
            showAlert.value = false
        }, 5000)
    }
})

interface Flash {
    success?: string | null;
    error?: string | null;
}

interface Config {
    registration_enabled?: boolean | null
    login_enabled?: boolean | null
}

declare module '@inertiajs/core' {
    interface PageProps {
        flash: Flash;
        config: Config;
    }
}

defineProps({
    records: Object,
    search_result: Object,
    search_term: String,
    search_button: Boolean,
    userCount: Number,
    recordCount: Number,
    transactionCount: Number,
});

</script>

<template>
    <Head title="Welcome" />
    <div class="min-h-screen flex flex-col bg-background text-[#1b1b18] dark:bg-[#0a0a0a]">
        <!-- Top Bar: Theme Switcher & Login -->
        <div class="flex justify-between items-center px-8 py-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <img src="/images/usep-logo-small.png" alt="USEP Logo" class="h-10 w-10" />
                <span class="font-bold text-xl">USeP Library</span>
            </div>
            <div class="flex items-center gap-4">
                <AppearanceTabs />
                <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-medium text-sm px-4 py-2 rounded bg-primary text-white">Dashboard</Link>
                <Link v-else :href="route('login')" class="font-medium text-sm px-4 py-2 rounded bg-primary text-white">Log in</Link>
            </div>
        </div>

        <!-- Hero Section -->
        <section class="hero-pattern py-16 flex flex-col items-center justify-center text-center">
            <h1 class="text-4xl font-bold mb-2">USeP Campus Library Tagum-Mabini</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">Your gateway to knowledge and resources</p>
            <div class="w-full max-w-xl">
                <WelcomeSearch :search_result="search_result" :search_term="search_term" :search_button="search_button" />
            </div>
        </section>

        <!-- Collection Cards Section -->
        <section class="py-12 px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="collection-card bg-white dark:bg-[#181818] rounded-lg shadow p-6 flex flex-col items-center">
                <div class="text-5xl font-bold count-animation">{{ recordCount }}</div>
                <div class="mt-2 text-lg font-semibold">Browse Collection</div>
                <div class="text-sm text-gray-500">Items available</div>
                <div class="mt-4 text-primary cursor-pointer hover:underline">Click for details</div>
            </div>
            <div class="collection-card bg-white dark:bg-[#181818] rounded-lg shadow p-6 flex flex-col items-center">
                <div class="text-5xl font-bold count-animation">1,245</div>
                <div class="mt-2 text-lg font-semibold">New Arrivals</div>
                <div class="text-sm text-gray-500">New items this month</div>
                <div class="mt-4 text-primary cursor-pointer hover:underline">Click for details</div>
            </div>
            <div class="collection-card bg-white dark:bg-[#181818] rounded-lg shadow p-6 flex flex-col items-center">
                <div class="text-5xl font-bold count-animation">892</div>
                <div class="mt-2 text-lg font-semibold">Top Picks</div>
                <div class="text-sm text-gray-500">Highly recommended</div>
                <div class="mt-4 text-primary cursor-pointer hover:underline">Click for details</div>
            </div>
        </section>

        <!-- Book Collection Section -->
        <section class="py-12 px-8">
            <h2 class="text-2xl font-bold mb-6">Book Collection</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="record in records?.data" :key="record.id">
                    <WelcomeBookDialog :record="record" />
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="mt-auto py-6 px-8 bg-gray-100 dark:bg-[#181818] text-center text-sm text-gray-600 dark:text-gray-400">
            <div class="mb-2 font-semibold">USEP Library Tagum-Mabini Campus</div>
            <div>© 2023 USEP Library. All rights reserved.</div>
        </footer>
    </div>
</template>

<style>
.hero-pattern {
    background-image: radial-gradient(rgba(0, 0, 0, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}
.collection-card {
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    z-index: 10;
}
.collection-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    z-index: 20;
}
.count-animation {
    animation: pulse 1s infinite;
}
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
</style>
