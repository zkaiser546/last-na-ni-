<script setup lang="ts">
  import { Head } from '@inertiajs/vue3'
  import AppLayout from '@/layouts/AppLayout.vue';

  defineProps<{}>()
</script>

<template>
    <Head title="Welcome" />

    <AppLayout>
        hi po
    </AppLayout>
</template>
