<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import { AlertCircle, X } from 'lucide-vue-next'
import { ref, onMounted } from 'vue'
import WelcomeBookDialog from '@/components/WelcomeBookDialog.vue';
import WelcomeSearch from '@/components/WelcomeSearch.vue';
import AppLogo from '@/components/AppLogo.vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';

// for alert
const page = usePage()
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
});

</script>

<template>
    <Head title="Welcome">
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-background text-[#1b1b18] lg:justify-center dark:bg-[#0a0a0a]">
        <Alert class="absolute top-5 right-5 w-fit pr-8" variant="destructive" v-if="page.props.flash.error && showAlert">
            <AlertCircle class="w-4 h-4" />
            <button @click="showAlert = false" class="absolute top-2 right-2 p-1 hover:bg-red-100 rounded-full transition-colors">
                <X class="w-4 h-4" />
            </button>
            <AlertTitle>Error</AlertTitle>
            <AlertDescription>
                {{ page.props.flash.error }}
            </AlertDescription>
        </Alert>
        <header class="flex  justify-between mb-6 w-full p-4 px-8 text-sm not-has-[nav]:hidden">
            <Link :href="route('logger')" class="flex items-center gap-2 dark:text-foreground">
                <AppLogo />
            </Link>
            <nav class="flex items-center justify-end gap-4">
                (temporary placement)
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
                        Log in
                    </Link>
                    <Link
                        v-if="$page.props.config.registration_enabled"
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>

        <!-- The content itself -->
        <div class="grid w-full opacity-100 transition-opacity duration-750 starting:opacity-0">

            <div class="p-8 min-w-full flex flex-col items-center">
                <WelcomeSearch :search_result="search_result" :search_term="search_term"
                               :search_button="search_button"
                />
            </div>

            <div class="w-full p-16" v-if="Object.keys(records?.data).length">
                <div class="grid grid-cols-3 gap-4">
                    <div v-for="record in records?.data" :key="record.id">
                        <WelcomeBookDialog :record="record" />
                    </div>
                </div>
            </div>

        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
