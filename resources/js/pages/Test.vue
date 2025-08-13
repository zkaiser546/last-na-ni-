<script setup lang="ts">
  import { Head } from '@inertiajs/vue3'
  import AppLayout from '@/layouts/AppLayout.vue';
  import Layout from '@/pages/users/Layout.vue';

  defineProps<{}>()
</script>

<template>
    <Head title="Welcome" />

    <AppLayout>
        <Layout>

        hi po, hello, padayon!
        </Layout>
    </AppLayout>
</template>
