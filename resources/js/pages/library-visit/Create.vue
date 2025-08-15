<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LoggerPatronSearch from '@/components/LoggerPatronSearch.vue';

defineProps({
    patron: Object,
    search_button: Boolean,
    is_logout: Boolean,
});
</script>

<template>
    <Head title="Library Logger" />
    <LoggerPatronSearch
        :patron="patron"
        :search_button="search_button"
        :is_logout="is_logout"
    />
</template>
