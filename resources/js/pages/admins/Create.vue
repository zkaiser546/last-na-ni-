<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { LoaderCircle } from 'lucide-vue-next';
import Layout from '@/layouts/users/Layout.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
    {
        title: 'Staff admins',
        href: '/users/admins',
    },
    {
        title: 'Create Admins',
        href: '/users/admins/create',
    },
];

const form = useForm({
    library_id: '',
    first_name: '',
    middle_initial: '',
    last_name: '',
    sex: '',
    contact_number: '',
    role_title: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('admins.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

</script>

<template>
    <Head title="Create Admins" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Layout>
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid grid-cols-4 gap-6">

                        <div class="grid gap-2">
                            <Label for="library_id">Library ID</Label>
                            <Input id="library_id" type="text" required :tabindex="1" v-model="form.library_id" placeholder="Library ID" />
                            <InputError :message="form.errors.library_id" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="first_name">First name</Label>
                            <Input id="first_name" type="text" required autofocus :tabindex="2" autocomplete="name" v-model="form.first_name" placeholder="First name" />
                            <InputError :message="form.errors.first_name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="middle_initial">Middle Initial</Label>
                            <Input id="middle_initial" type="text" :tabindex="3" v-model="form.middle_initial" placeholder="Middle Initial" maxlength="1" />
                            <InputError :message="form.errors.middle_initial" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="last_name">Last name</Label>
                            <Input id="last_name" type="text" required :tabindex="4" autocomplete="name" v-model="form.last_name" placeholder="Last name" />
                            <InputError :message="form.errors.last_name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="sex">Sex</Label>
                            <Select v-model="form.sex" required>
                                <SelectTrigger id="sex" :tabindex="5">
                                    <SelectValue placeholder="Select sex" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="male">Male</SelectItem>
                                    <SelectItem value="female">Female</SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.sex" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="contact_number">Contact Number</Label>
                            <Input id="contact_number" type="tel" required :tabindex="6" v-model="form.contact_number" placeholder="Contact Number" />
                            <InputError :message="form.errors.contact_number" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="role_title">Role Title</Label>
                            <Input id="role_title" type="text" required :tabindex="7" v-model="form.role_title" placeholder="Role Title" />
                            <InputError :message="form.errors.role_title" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="email">Email address</Label>
                            <Input id="email" type="email" required :tabindex="8" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password">Password</Label>
                            <Input
                                id="password"
                                type="password"
                                required
                                :tabindex="9"
                                autocomplete="new-password"
                                v-model="form.password"
                                placeholder="Password"
                            />
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password_confirmation">Confirm password</Label>
                            <Input
                                id="password_confirmation"
                                type="password"
                                required
                                :tabindex="10"
                                autocomplete="new-password"
                                v-model="form.password_confirmation"
                                placeholder="Confirm password"
                            />
                            <InputError :message="form.errors.password_confirmation" />
                        </div>

                        <Button type="submit" class="mt-2 w-full" tabindex="11" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            Create account
                        </Button>
                    </div>
                </form>
            </div>
        </Layout>
    </AppLayout>
</template>
