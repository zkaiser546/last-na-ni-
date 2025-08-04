<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
  import AppLayout from '@/layouts/AppLayout.vue';
  import { LoaderCircle } from 'lucide-vue-next';
  import { Label } from '@/components/ui/label';
  import { Input } from '@/components/ui/input';
  import InputError from '@/components/InputError.vue';
  import { Button } from '@/components/ui/button';
  import type { BreadcrumbItem } from '@/types';

    const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
    {
        title: 'Users Import',
        href: '/users/import',
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
    form.post(route('users.import.store'));
};

</script>

<template>
    <Head title="Import Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid grid-cols-4 gap-6">
                    <div class="grid gap-2">
                        <Label for="csv_file">CSV File</Label>
                        <Input required type="file" @input="handleFileInput" />
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>
                        <InputError :message="form.errors.csv_file" />
                        <Button type="submit" variant="outline" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                            <span v-if="form.processing" class="flex gap-2"><LoaderCircle class="h-4 w-4 animate-spin" />1 min/1000 records</span>
                            <span v-else>Import Users</span>
                        </Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
