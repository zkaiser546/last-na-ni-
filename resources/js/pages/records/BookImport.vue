<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
  import type { BreadcrumbItem } from '@/types';
  import AppLayout from '@/layouts/AppLayout.vue';
  import InputError from '@/components/InputError.vue';
  import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { LoaderCircle } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';

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
        csv_file: null as File | null,
    });

    const handleFileInput = (event: Event) => {
        const target = event.target as HTMLInputElement;
        if (target && target.files && target.files.length > 0) {
            form.csv_file = target.files[0];
        }
    };

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
                        <Input type="file" @input="handleFileInput" />
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>
                        <InputError :message="form.errors.csv_file" />
                        <Button type="submit" variant="outline" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            Import Books
                        </Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
