<script setup lang="ts">
  import { Head } from '@inertiajs/vue3'
  import AppLayout from '@/layouts/AppLayout.vue';
  import type { BreadcrumbItem } from '@/types';

  const breadcrumbs: BreadcrumbItem[] = [
      {
          title: 'Borrowings',
          href: '/borrowings',
      },
      {
          title: 'Borrow/Return',
          href: '/borrow-return',
      },
  ];

</script>

<template>
    <Head title="Create Students" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        borrow/return here
        </div>
    </AppLayout>
</template>
