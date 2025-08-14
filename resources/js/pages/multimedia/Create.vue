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
import { Textarea } from '@/components/ui/textarea';
import AuthorsTagsInput from '@/components/AuthorsTagsInput.vue';
import EditorsTagsInput from '@/components/EditorsTagsInput.vue';
import SubjectTagsInput from '@/components/SubjectTagsInput.vue';

// Props from Inertia
const props = defineProps<{
    languages: { id: number; name: string }[];
    collectionTypes: { id: number; name: string }[];
    sources: { id: number; name: string; key: string }[];
    acquisitionStatuses: { id: number; name: string }[];
    conditions: { id: number; name: string }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Records', href: '/records' },
    { title: 'Multimedia', href: '/records/multimedia' },
    { title: 'Create Multimedia', href: '/records/multimedia/create' },
];

const form = useForm({
    accession_number: '',
    title: '',
    authors: [],
    editors: [],
    publication_year: '',
    copyright_year: '',
    publisher: '',
    language: '',
    collection_type: '',
    duration: '',
    cover_image: null,
    source: '',
    purchase_amount: '',
    lot_cost: '',
    supplier: '',
    donated_by: '',
    acquisition_status: '',
    condition: '',
    overview: '',
    subject_headings: [],
});

const submit = () => {
    form.post(route('multimedia.store'));
};
</script>

<template>
    <Head title="Create Book" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <RecordsLayout>
            <div class="flex flex-col gap-6 p-6 bg-white rounded-xl shadow-sm overflow-x-auto">
                <form @submit.prevent="submit" class="flex flex-col gap-8 max-w-5xl mx-auto">

                    <!-- Basic Information -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold">Basic Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="grid gap-2">
                                <Label for="accession_number">Accession Number</Label>
                                <Input id="accession_number" type="text" required v-model="form.accession_number" />
                                <InputError :message="form.errors.accession_number" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="title">Title</Label>
                                <Input id="title" type="text" required v-model="form.title" />
                                <InputError :message="form.errors.title" />
                            </div>
                            <div class="grid gap-2">
                                <div class="flex gap-2">
                                    <Label for="authors">Author/s</Label>
                                    <span class="text-sm text-gray-500">(Hit 'ENTER' for each author)</span>
                                </div>
                                <AuthorsTagsInput v-model="form.authors" />
                                <InputError :message="form.errors.authors" />
                            </div>
                            <div class="grid gap-2">
                                <div class="flex gap-2">
                                    <Label for="editors">Editor/s</Label>
                                    <span class="text-sm text-gray-500">(Hit 'ENTER' for each editor)</span>
                                </div>
                                <EditorsTagsInput v-model="form.editors" />
                                <InputError :message="form.errors.editors" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="publication_year">Year of Publication</Label>
                                <Input id="publication_year" type="number" required v-model="form.publication_year" />
                                <InputError :message="form.errors.publication_year" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="copyright_year">Year of Copyright</Label>
                                <Input id="copyright_year" type="number" v-model="form.copyright_year" />
                                <InputError :message="form.errors.copyright_year" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="publisher">Publisher</Label>
                                <Input id="publisher" type="text" required v-model="form.publisher" />
                                <InputError :message="form.errors.publisher" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="language">Language</Label>
                                <Select v-model="form.language">
                                    <SelectTrigger id="language">
                                        <SelectValue placeholder="Select language" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="lang in props.languages"
                                            :key="lang.id"
                                            :value="lang.id"
                                        >
                                            {{ lang.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.language" />
                            </div>
                        </div>
                    </section>

                    <!-- Technical Specifications -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold">Technical Specifications</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="grid gap-2">
                                <Label for="collection_type">Collection Type</Label>
                                <Select v-model="form.collection_type">
                                    <SelectTrigger id="collection_type">
                                        <SelectValue placeholder="Select collection type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="type in props.collectionTypes"
                                            :key="type.id"
                                            :value="type.id"
                                        >
                                            {{ type.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.collection_type" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="duration">Duration</Label>
                                <Input id="duration" type="text" v-model="form.duration" />
                                <InputError :message="form.errors.duration" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="cover_image">Cover Image</Label>
                                <Input id="cover_image" type="file" @change="e => form.cover_image = e.target.files[0]" />
                                <InputError :message="form.errors.cover_image" />
                            </div>
                        </div>
                    </section>

                    <!-- Administrative Information -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold">Administrative Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="grid gap-2">
                                <Label for="source">Source</Label>
                                <Select v-model="form.source" required>
                                    <SelectTrigger id="source">
                                        <SelectValue placeholder="Select source" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="src in props.sources"
                                            :key="src.id"
                                            :value="src.key"
                                        >
                                            {{ src.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.source" />
                            </div>

                            <!-- Purchase-specific -->
                            <template v-if="form.source === 'purchase'">
                                <div class="grid gap-2">
                                    <Label for="purchase_amount">Purchase Amount</Label>
                                    <Input id="purchase_amount" type="number" step="0.01" v-model="form.purchase_amount" />
                                    <InputError :message="form.errors.purchase_amount" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="lot_cost">Lot Cost</Label>
                                    <Input id="lot_cost" type="number" step="0.01" v-model="form.lot_cost" />
                                    <InputError :message="form.errors.lot_cost" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="supplier">Supplier</Label>
                                    <Input id="supplier" type="text" v-model="form.supplier" />
                                    <InputError :message="form.errors.supplier" />
                                </div>
                            </template>

                            <!-- Donation-specific -->
                            <template v-if="form.source === 'donation'">
                                <div class="grid gap-2">
                                    <Label for="donated_by">Donated By</Label>
                                    <Input id="donated_by" type="text" v-model="form.donated_by" />
                                    <InputError :message="form.errors.donated_by" />
                                </div>
                            </template>

                            <div class="grid gap-2">
                                <Label for="acquisition_status">Acquisition Status</Label>
                                <Select v-model="form.acquisition_status">
                                    <SelectTrigger id="acquisition_status">
                                        <SelectValue placeholder="Select acquisition status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="status in props.acquisitionStatuses"
                                            :key="status.id"
                                            :value="status.id"
                                        >
                                            {{ status.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.acquisition_status" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="condition">Condition</Label>
                                <Select v-model="form.condition">
                                    <SelectTrigger id="condition">
                                        <SelectValue placeholder="Select condition" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="cond in props.conditions"
                                            :key="cond.id"
                                            :value="cond.id"
                                        >
                                            {{ cond.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.condition" />
                            </div>
                        </div>
                    </section>

                    <!-- Content Description -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold">Content Description</h2>
                        <div class="grid gap-6">
                            <div class="grid gap-2">
                                <Label for="overview">Overview</Label>
                                <Textarea id="overview" rows="4" v-model="form.overview" />
                                <InputError :message="form.errors.overview" />
                            </div>
                            <div class="grid gap-2">
                                <div class="flex gap-2">
                                    <Label for="subject_headings">Subject Heading/s</Label>
                                    <span class="text-sm text-gray-500">(Hit 'ENTER' for each subject)</span>
                                </div>
                                <SubjectTagsInput v-model="form.subject_headings" />
                                <InputError :message="form.errors.subject_headings" />
                            </div>
                        </div>
                    </section>

                    <!-- Submit -->
                    <div class="flex justify-end pt-4">
                        <Button type="submit" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                            Create Book Record
                        </Button>
                    </div>
                </form>
            </div>
        </RecordsLayout>
    </AppLayout>
</template>
