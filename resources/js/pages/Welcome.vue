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
        <!-- Top Bar: Theme Switcher & Login/Register -->
        <div class="flex justify-between items-center px-8 py-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <img src="/images/usep-logo-small.png" alt="USEP Logo" class="h-10 w-10" />
                <span class="font-bold text-xl text-gray-900 dark:text-gray-100">USeP Library</span>
            </div>
            <div class="flex items-center gap-4">
                <AppearanceTabs />
                <template v-if="$page.props.auth.user">
                    <Link
                        :href="route('dashboard')"
                        class="font-medium text-sm px-4 py-2 rounded bg-primary text-white"
                    >
                        Dashboard
                    </Link>
                </template>
                <template v-else>
                    <Link
                        v-if="$page.props.config.login_enabled"
                        :href="route('login')"
                        class="font-medium text-sm px-4 py-2 rounded bg-primary text-white"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="$page.props.config.registration_enabled"
                        :href="route('register')"
                        class="font-medium text-sm px-4 py-2 rounded bg-secondary text-white"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </div>


        <!-- Hero Section -->
        <section class="hero-pattern py-16 flex flex-col items-center justify-center text-center">
            <h1 class="text-4xl font-bold mb-2 text-gray-900 dark:text-gray-100">USeP Campus Library Tagum-Mabini</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">Your gateway to knowledge and resources</p>
            <div class="w-full max-w-xl">
                <WelcomeSearch :search_result="search_result" :search_term="search_term" :search_button="search_button" />
            </div>
        </section>

        <!-- Collection Cards Section -->
        <section class="py-16 px-8 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="collection-card bg-white dark:bg-[#181818] rounded-xl shadow-lg p-10 flex flex-col items-center min-h-[220px] hover:scale-105 transition-transform">
                <div class="text-6xl font-extrabold count-animation text-usepmaroon dark:text-usepgold mb-4">{{ recordCount }}</div>
                <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">Browse Collection</div>
                <div class="text-base text-gray-500 dark:text-gray-300 mb-2">Items available in the library</div>
                <div class="w-16 h-1 bg-usepgold rounded-full mt-4"></div>
            </div>
            <div class="collection-card bg-white dark:bg-[#181818] rounded-xl shadow-lg p-10 flex flex-col items-center min-h-[220px] hover:scale-105 transition-transform">
                <div class="text-6xl font-extrabold count-animation text-usepmaroon dark:text-usepgold mb-4">1,245</div>
                <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">New Arrivals</div>
                <div class="text-base text-gray-500 dark:text-gray-300 mb-2">Added this month</div>
                <div class="w-16 h-1 bg-usepgold rounded-full mt-4"></div>
            </div>
            <div class="collection-card bg-white dark:bg-[#181818] rounded-xl shadow-lg p-10 flex flex-col items-center min-h-[220px] hover:scale-105 transition-transform">
                <div class="text-6xl font-extrabold count-animation text-usepmaroon dark:text-usepgold mb-4">892</div>
                <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">Top Picks</div>
                <div class="text-base text-gray-500 dark:text-gray-300 mb-2">Highly recommended by staff</div>
                <div class="w-16 h-1 bg-usepgold rounded-full mt-4"></div>
            </div>
        </section>

        <!-- Book Collection Section -->
        <section class="py-12 px-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Book Collection</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="record in records?.data" :key="record.id">
                    <WelcomeBookDialog :record="record" />
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-usepmaroon text-white py-10 w-full mt-12 shadow-lg">
            <div class="container mx-auto px-0 md:px-0">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="mb-6 md:mb-0 text-left">
                        <h3 class="text-2xl font-bold tracking-wide mb-1">USEP Library</h3>
                        <p class="text-usepgold/80 text-lg">Tagum-Mabini Campus</p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="bg-usepgold/20 hover:bg-usepgold/40 rounded-full p-3 transition flex items-center justify-center">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="bg-usepgold/20 hover:bg-usepgold/40 rounded-full p-3 transition flex items-center justify-center">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="bg-usepgold/20 hover:bg-usepgold/40 rounded-full p-3 transition flex items-center justify-center">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
                <div class="border-t border-usepgold/40 mt-10 pt-6 text-sm text-center text-usepgold/80 w-full">
                    &copy; 2025 USEP Library. All rights reserved.
                </div>
            </div>
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
