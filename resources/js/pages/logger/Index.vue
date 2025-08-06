<script setup lang="ts">
  import { Head } from '@inertiajs/vue3'
  import AppLayout from '@/layouts/AppLayout.vue';
  import type { BreadcrumbItem } from '@/types';
  import LatestLoggerTransactions from '@/components/LatestLoggerTransactions.vue';

  const breadcrumbs: BreadcrumbItem[] = [
      {
          title: 'Logger',
          href: '/logger',
      },
  ];

  defineProps({
      visits: Object,
  })

</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <h1 class="text-2xl">Latest logger records</h1>

            <LatestLoggerTransactions :transactions="visits"/>
        </div>
    </AppLayout>

</template>
