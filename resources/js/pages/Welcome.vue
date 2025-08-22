<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { AlertCircle, X } from 'lucide-vue-next';
import WelcomeBookDialog from '@/components/WelcomeBookDialog.vue';
import WelcomeSearch from '@/components/WelcomeSearch.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { ref, watch, onMounted } from 'vue';

interface Flash {
    success?: string | null;
    error?: string | null;
}

interface Config {
    registration_enabled?: boolean | null;
    login_enabled?: boolean | null;
}

declare module '@inertiajs/core' {
    interface PageProps {
        flash: Flash;
        config: Config;
    }
}

const props = defineProps({
    records: Object,
    search_result: Object,
    search_term: String,
    search_button: Boolean,
    userCount: Number,
    recordCount: Number,
    transactionCount: Number,
});

// for alert
const page = usePage();
const name = page.props.name;
const showAlert = ref(true);

// Animated counters
const displayedRecordCount = ref(0);
const displayedNewArrivalsCount = ref(0);
const displayedTopPicksCount = ref(0);

function animateCount(target: number, refToUpdate: any) {
    const start = 0;
    const duration = 1200;
    const startTime = performance.now();

    function update(now: number) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        refToUpdate.value = Math.floor(start + (target - start) * progress);
        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            refToUpdate.value = target;
        }
    }
    requestAnimationFrame(update);
}

onMounted(() => {
    animateCount(props.recordCount, displayedRecordCount);
    animateCount(1245, displayedNewArrivalsCount);
    animateCount(892, displayedTopPicksCount);

    if (page.props.flash.error) {
        setTimeout(() => {
            showAlert.value = false;
        }, 5000);
    }
});

watch(() => props.recordCount, (newVal) => {
    animateCount(Number(newVal), displayedRecordCount);
});
</script>

<template>
    <Head title="Welcome" />
    <div class="min-h-screen flex flex-col bg-background text-[#1b1b18] dark:bg-[#0a0a0a]">

        <!-- Top Bar -->
        <header class="flex justify-between items-center px-8 py-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <img src="/storage/images/usepLogo.svg" alt="USEP Logo" class="h-10 w-10" />
                <span class="font-bold text-xl text-gray-900 dark:text-gray-100">USeP Library</span>
            </div>

            <div class="flex items-center gap-4">
                <AppearanceTabs />
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        v-if="$page.props.config.login_enabled"
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        <Button class="w-[120px]">Log in</Button>
                    </Link>
                    <Link
                        v-if="$page.props.config.registration_enabled"
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        <Button class="w-[120px]">Register</Button>
                    </Link>
                </template>
            </div>
        </header>

        <!-- Hero Section -->
        <section
            class="relative py-16 flex flex-col items-center justify-center text-center bg-cover bg-center"
            style="background-image: url('/storage/images/usepBG.svg');"
        >
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative z-10 max-w-2xl px-6">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white drop-shadow-lg">
                    USeP Campus Library Tagum-Mabini
                </h1>
                <p class="text-lg md:text-xl text-gray-200 mb-8">
                    Your gateway to knowledge and resources
                </p>
                <div class="w-full">
                    <WelcomeSearch
                        :search_result="search_result"
                        :search_term="search_term"
                        :search_button="search_button"
                    />
                </div>
            </div>
        </section>

        <!-- Collection Cards -->
        <section class="py-16 px-8 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="collection-card bg-white dark:bg-[#181818] rounded-xl shadow-lg p-10 flex flex-col items-center min-h-[220px] hover:scale-105 transition-transform">
                <div class="text-6xl font-extrabold count-animation text-usepmaroon dark:text-usepgold mb-4">
                    {{ displayedRecordCount.toLocaleString() }}
                </div>
                <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">Browse Collection</div>
                <div class="text-base text-gray-500 dark:text-gray-300 mb-2">Items available in the library</div>
                <div class="w-16 h-1 bg-usepgold rounded-full mt-4"></div>
            </div>
            <div class="collection-card bg-white dark:bg-[#181818] rounded-xl shadow-lg p-10 flex flex-col items-center min-h-[220px] hover:scale-105 transition-transform">
                <div class="text-6xl font-extrabold count-animation text-usepmaroon dark:text-usepgold mb-4">
                    {{ displayedNewArrivalsCount.toLocaleString() }}
                </div>
                <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">New Arrivals</div>
                <div class="text-base text-gray-500 dark:text-gray-300 mb-2">Added this month</div>
                <div class="w-16 h-1 bg-usepgold rounded-full mt-4"></div>
            </div>
            <div class="collection-card bg-white dark:bg-[#181818] rounded-xl shadow-lg p-10 flex flex-col items-center min-h-[220px] hover:scale-105 transition-transform">
                <div class="text-6xl font-extrabold count-animation text-usepmaroon dark:text-usepgold mb-4">
                    {{ displayedTopPicksCount.toLocaleString() }}
                </div>
                <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">Top Picks</div>
                <div class="text-base text-gray-500 dark:text-gray-300 mb-2">Highly recommended by staff</div>
                <div class="w-16 h-1 bg-usepgold rounded-full mt-4"></div>
            </div>
        </section>

        <!-- Latest in Book Collections -->
        <div class="w-full p-16" v-if="Object.keys(records?.data).length">
            <h3 class="text-3xl text-center mb-8">Latest in Book Collections</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div v-for="record in records?.data" :key="record.id">
                    <WelcomeBookDialog :record="record" />
                </div>
            </div>
        </div>

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
