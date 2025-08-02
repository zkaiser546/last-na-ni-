<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
  import type { BreadcrumbItem } from '@/types';
  import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';

  const breadcrumbs: BreadcrumbItem[] = [
      {
          title: 'Borrowings',
          href: '/borrowings',
      },
  ];

</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <h1 class="text-4xl">FUNCTIONALITY lang ha</h1>

            <div class="flex gap-2">
                <Link :href="route('borrowings.create')">
                    <Button variant="secondary">Borrow Book</Button>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
