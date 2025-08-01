<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
  import type { BreadcrumbItem } from '@/types';
  import AppLayout from '@/layouts/AppLayout.vue';
  import InputError from '@/components/InputError.vue';
  import { Label } from '@/components/ui/label';

  const breadcrumbs: BreadcrumbItem[] = [
      {
          title: 'Records',
          href: '/records',
      },
      {
          title: 'Book Import',
          href: '/records/books/import',
      },
  ];

  const form = useForm({
      csv_file: null,
  });

  const submit = () => {
      form.post(route('books.import.store'));
  };

</script>

<template>
    <Head title="Books" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid grid-cols-4 gap-6">
                    <div class="grid gap-2">
                        <Label for="csv_file">CSV File</Label>
                        <input type="file" @input="form.csv_file = $event.target.files[0]" />
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>
                        <InputError :message="form.errors.csv_file" />
                        <button type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
