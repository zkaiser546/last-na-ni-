<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
  import AppLayout from '@/layouts/AppLayout.vue';
  import { Input } from '@/components/ui/input';
  import { Button } from '@/components/ui/button';
  import InputError from '@/components/InputError.vue';
  import { LoaderCircle } from 'lucide-vue-next';
  import { Label } from '@/components/ui/label';
  import type { BreadcrumbItem } from '@/types';

  const breadcrumbs: BreadcrumbItem[] = [
      {
          title: 'Users',
          href: '/users',
      },
      {
          title: 'Create Students',
          href: '/students/create',
      },
  ];

  const form = useForm({
      first_name: '',
      last_name: '',
      email: '',
      student_id: '',
  });

const submit = () => {
    form.post(route('grad-students.store'), {});
};

</script>

<template>
    <Head title="Create Graduate School Students" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid grid-cols-4 gap-6">
                    <div class="grid gap-2">
                        <Label for="first_name">First name</Label>
                        <Input
                            id="first_name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="first_name"
                            v-model="form.first_name"
                            placeholder="First name"
                        />
                        <InputError :message="form.errors.first_name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="last_name">Last name</Label>
                        <Input
                            id="last_name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="last_name"
                            v-model="form.last_name"
                            placeholder="Last name"
                        />
                        <InputError :message="form.errors.last_name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="student_id">Student ID</Label>
                        <Input
                            id="student_id"
                            type="number"
                            required
                            autofocus
                            :tabindex="1"
                            v-model="form.student_id"
                            placeholder="Student ID"
                        />
                        <InputError :message="form.errors.student_id" />
                    </div>

                    <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Create Student
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
