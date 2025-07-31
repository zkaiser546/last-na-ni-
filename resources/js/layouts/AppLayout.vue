<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { AlertCircle, CircleCheckBig, X } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

interface Flash {
    success?: string | null;
    error?: string | null;
}

declare module '@inertiajs/core' {
    interface PageProps {
        flash: Flash;
    }
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

// for alert
const page = usePage()
const showAlert = ref(true)
onMounted(() => {
    if (page.props.flash.error) {
        setTimeout(() => {
            showAlert.value = false
        }, 5000)
    }
    if (page.props.flash.success) {
        setTimeout(() => {
            showAlert.value = false
        }, 5000)
    }
})
</script>

<template>
    <Alert class="absolute top-5 right-5 w-fit pr-8 z-30" variant="destructive" v-if="page.props.flash.error && showAlert">
        <AlertCircle class="w-4 h-4" />
        <button @click="showAlert = false" class="absolute top-2 right-2 p-1 hover:bg-red-100 rounded-full transition-colors">
            <X class="w-4 h-4" />
        </button>
        <AlertTitle>Error</AlertTitle>
        <AlertDescription>
            {{ page.props.flash.error }}
        </AlertDescription>
    </Alert>
    <Alert class="absolute border-2 border-green-500 top-5 right-5 w-fit max-w-lg pr-8 z-30" v-if="page.props.flash.success && showAlert">
        <CircleCheckBig />
        <button @click="showAlert = false" class="absolute top-2 right-2 p-1 hover:bg-red-100 rounded-full transition-colors">
            <X class="w-4 h-4" />
        </button>
        <AlertTitle>Success</AlertTitle>
        <AlertDescription>
            {{ page.props.flash.success }}
        </AlertDescription>
    </Alert>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
