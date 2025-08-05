<script setup lang="ts">

  import { Head } from '@inertiajs/vue3';
  import LoggerPatronSearch from '@/components/LoggerPatronSearch.vue';

  defineProps<{}>()
</script>

<template>
    <Head title="Patron Logger"></Head>
    <div class="flex min-h-screen flex-col items-center text-[#1b1b18] lg:justify-center dark:bg-[#0a0a0a]">

        <div class="grid w-full opacity-100 transition-opacity duration-750 starting:opacity-0">
            <div class="p-8 min-w-full flex flex-col items-center">
                <LoggerPatronSearch :search_result="search_result" :search_term="search_term"
                               :search_button="search_button"
                />
            </div>
        </div>
    </div>
</template>
