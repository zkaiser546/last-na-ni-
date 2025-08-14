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
import RecordsLayout from '@/layouts/records/Layout.vue';

// Define the props passed from the controller
defineProps<{
    programs: { id: number; code: string; name: string }[];
    majors: { id: number; name: string }[];
    colleges: { id: number; code: string; name: string }[];
}>();


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Records', href: '/records' },
    { title: 'Books', href: '/records/books' },
    { title: 'Create Book', href: '/records/books/create' },
];

// Initialize the form with all necessary fields
const form = useForm({
    library_id: '',
    first_name: '',
    middle_initial: '',
    last_name: '',
    sex: '',
    contact_number: '',
    email: '',
    student_type: '',
    school_id: '',
    college_id: null,
    program_id: null,
    major_id: null,
});

// Handle form submission
const submit = () => {
    // Corrected the route to store a student
    form.post(route('students.store'));
};
</script>

<template>
    <!-- Corrected the Head title -->
    <Head title="Create Book" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <RecordsLayout>
            <div class="flex h-full flex-1 flex-col gap-6 p-6 bg-white rounded-xl shadow-sm overflow-x-auto">
                <form @submit.prevent="submit" class="flex flex-col gap-8 max-w-4xl mx-auto">

                    <!-- Personal Information Section -->
                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="grid gap-2">
                                <Label for="library_id" class="text-sm font-medium">Library ID</Label>
                                <Input id="library_id" type="number" required :tabindex="1" v-model="form.library_id" placeholder="Library ID" class="h-10" />
                                <InputError :message="form.errors.library_id" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="first_name" class="text-sm font-medium">First Name</Label>
                                <Input id="first_name" type="text" required autofocus :tabindex="2" autocomplete="given-name" v-model="form.first_name" placeholder="First name" class="h-10" />
                                <InputError :message="form.errors.first_name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="middle_initial" class="text-sm font-medium">Middle Initial</Label>
                                <Input id="middle_initial" type="text" :tabindex="3" v-model="form.middle_initial" placeholder="MI" maxlength="1" class="h-10" />
                                <InputError :message="form.errors.middle_initial" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="last_name" class="text-sm font-medium">Last Name</Label>
                                <Input id="last_name" type="text" required :tabindex="4" autocomplete="family-name" v-model="form.last_name" placeholder="Last name" class="h-10" />
                                <InputError :message="form.errors.last_name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="sex" class="text-sm font-medium">Sex</Label>
                                <Select v-model="form.sex" required>
                                    <SelectTrigger id="sex" :tabindex="5" class="h-10">
                                        <SelectValue placeholder="Select sex" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="m">Male</SelectItem>
                                        <SelectItem value="f">Female</SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.sex" />
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900">Contact Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="grid gap-2">
                                <Label for="contact_number" class="text-sm font-medium">Contact Number</Label>
                                <Input id="contact_number" type="tel" :tabindex="6" v-model="form.contact_number" placeholder="Contact Number" class="h-10" />
                                <InputError :message="form.errors.contact_number" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email" class="text-sm font-medium">Email Address</Label>
                                <Input id="email" type="email" required :tabindex="7" autocomplete="email" v-model="form.email" placeholder="email@example.com" class="h-10" />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information Section -->
                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900">Academic Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Student ID Input -->
                            <div class="grid gap-2">
                                <Label for="school_id" class="text-sm font-medium">School ID</Label>
                                <Input
                                    id="school_id"
                                    type="text"
                                    required
                                    v-model="form.school_id"
                                    placeholder="e.g., 2025-12345"
                                    class="h-10"
                                />
                                <InputError :message="form.errors.school_id" />
                            </div>

                            <!-- College Select Input -->
                            <div class="grid gap-2">
                                <Label for="college_id" class="text-sm font-medium">College</Label>
                                <Select v-model="form.college_id" required>
                                    <SelectTrigger id="college_id" class="h-10">
                                        <SelectValue placeholder="Select college" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="college in colleges" :key="college.id" :value="college.id">
                                            {{ college.code }} - {{ college.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.college_id" />
                            </div>

                            <!-- Program Select Input -->
                            <div class="grid gap-2">
                                <Label for="program_id" class="text-sm font-medium">Program</Label>
                                <Select v-model="form.program_id" required>
                                    <SelectTrigger id="program_id" class="h-10">
                                        <SelectValue placeholder="Select program" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="program in programs" :key="program.id" :value="program.id">
                                            {{ program.code }} - {{ program.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.program_id" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <!-- Major Select Input -->
                            <div class="grid gap-2">
                                <Label for="major_id" class="text-sm font-medium">Major (if applicable)</Label>
                                <Select v-model="form.major_id">
                                    <SelectTrigger id="major_id" class="h-10">
                                        <SelectValue placeholder="Select major" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="major in majors" :key="major.id" :value="major.id">
                                            {{ major.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.major_id" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="office_id" class="text-sm font-medium">Student Type</Label>
                                <Select v-model="form.student_type" required>
                                    <SelectTrigger id="student_type" class="h-10">
                                        <SelectValue placeholder="Select student type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="undergraduate">Undergraduate</SelectItem>
                                        <SelectItem value="graduate">Graduate</SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.student_type" />
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <Button type="submit" class="w-full md:w-auto px-8 py-2" tabindex="9" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                            Create Student Account
                        </Button>
                    </div>
                </form>
            </div>
        </RecordsLayout>
    </AppLayout>
</template>
