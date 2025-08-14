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

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Records', href: '/records' },
    { title: 'Books', href: '/records/books' },
    { title: 'Create Book', href: '/records/books/create' },
];

// Form fields
const form = useForm({
    accession_number: '',
    title: '',
    authors: [],
    publication_year: '',
    publisher: '',
    place_of_publication: '',
    isbn: '',
    additional_authors: '',
    editor: '',
    series_title: '',
    ddc_classification: '',
    call_number: '',
    physical_location: '',
    location_symbol: '',
    edition: '',
    cover_type: '',
    book_cover_image: null,
    date_acquired: new Date().toISOString().split('T')[0],
    source: '',
    purchase_amount: '',
    acquisition_status: '',
    table_of_contents: '',
    summary_abstract: '',
    additional_notes: ''
});

// Submit handler
const submit = () => {
    form.post(route('books.store'));
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
                        <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="grid gap-2">
                                <Label for="title">Title</Label>
                                <Input id="title" type="text" required v-model="form.title" />
                                <InputError :message="form.errors.title" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="author">Authors</Label>
                                <Input id="author" type="text" required v-model="form.authors" />
                                <InputError :message="form.errors.authors" />
                            </div>
                            <AuthorsTagsInput />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="grid gap-2">
                                <Label for="accession_number">Accession Number</Label>
                                <Input id="accession_number" type="number" required v-model="form.accession_number" />
                                <InputError :message="form.errors.accession_number" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="publication_year">Publication Year</Label>
                                <Input id="publication_year" type="number" required v-model="form.publication_year" />
                                <InputError :message="form.errors.publication_year" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="publisher">Publisher</Label>
                                <Input id="publisher" type="text" required v-model="form.publisher" />
                                <InputError :message="form.errors.publisher" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="place_of_publication">Place of Publication</Label>
                                <Input id="place_of_publication" type="text" required v-model="form.place_of_publication" />
                                <InputError :message="form.errors.place_of_publication" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="isbn">ISBN</Label>
                                <Input id="isbn" type="text" required v-model="form.isbn" />
                                <InputError :message="form.errors.isbn" />
                            </div>
                        </div>
                    </section>

                    <!-- Additional Authors & Contributors -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900">Additional Authors & Contributors</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="grid gap-2">
                                <Label for="additional_authors">Additional Authors</Label>
                                <Input id="additional_authors" type="text" v-model="form.additional_authors" />
                                <InputError :message="form.errors.additional_authors" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="editor">Editor</Label>
                                <Input id="editor" type="text" v-model="form.editor" />
                                <InputError :message="form.errors.editor" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="series_title">Series Title</Label>
                                <Input id="series_title" type="text" v-model="form.series_title" />
                                <InputError :message="form.errors.series_title" />
                            </div>
                        </div>
                    </section>

                    <!-- Classification & Location -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900">Classification & Location</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="grid gap-2">
                                <Label for="ddc_classification">DDC Classification</Label>
                                <Select v-model="form.ddc_classification" required>
                                    <SelectTrigger id="ddc_classification">
                                        <SelectValue placeholder="Select classification" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="000">000 – General Works</SelectItem>
                                        <!-- More options -->
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.ddc_classification" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="call_number">Call Number</Label>
                                <Input id="call_number" type="text" readonly v-model="form.call_number" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="physical_location">Physical Location</Label>
                                <Select v-model="form.physical_location">
                                    <SelectTrigger id="physical_location">
                                        <SelectValue placeholder="Select location" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="main">Main Library</SelectItem>
                                        <!-- More options -->
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="grid gap-2">
                                <Label for="location_symbol">Location Symbol</Label>
                                <Input id="location_symbol" type="text" readonly v-model="form.location_symbol" />
                            </div>
                        </div>
                    </section>

                    <!-- Physical Description -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900">Physical Description</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="grid gap-2">
                                <Label for="edition">Edition</Label>
                                <Input id="edition" type="text" v-model="form.edition" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="cover_type">Cover Type</Label>
                                <Select v-model="form.cover_type">
                                    <SelectTrigger id="cover_type">
                                        <SelectValue placeholder="Select cover type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="hardcover">Hardcover</SelectItem>
                                        <SelectItem value="paperback">Paperback</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="grid gap-2">
                                <Label for="book_cover_image">Book Cover Image</Label>
                                <Input id="book_cover_image" type="file" @change="e => form.book_cover_image = e.target.files[0]" />
                            </div>
                        </div>
                    </section>

                    <!-- Administrative Information -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900">Administrative Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="grid gap-2">
                                <Label for="date_acquired">Date Acquired</Label>
                                <Input id="date_acquired" type="date" v-model="form.date_acquired" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="source">Source</Label>
                                <Select v-model="form.source">
                                    <SelectTrigger id="source">
                                        <SelectValue placeholder="Select source" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="purchase">Purchase</SelectItem>
                                        <SelectItem value="donation">Donation</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="grid gap-2">
                                <Label for="purchase_amount">Purchase Amount</Label>
                                <Input id="purchase_amount" type="number" step="0.01" v-model="form.purchase_amount" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="acquisition_status">Acquisition Status</Label>
                                <Select v-model="form.acquisition_status">
                                    <SelectTrigger id="acquisition_status">
                                        <SelectValue placeholder="Select status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="available">Available</SelectItem>
                                        <SelectItem value="pending">Pending</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </section>

                    <!-- Content Description -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900">Content Description</h2>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="grid gap-2">
                                <Label for="table_of_contents">Table of Contents</Label>
                                <Textarea id="table_of_contents" v-model="form.table_of_contents" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="summary_abstract">Summary / Abstract</Label>
                                <Textarea id="summary_abstract" v-model="form.summary_abstract" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="additional_notes">Additional Notes</Label>
                                <Textarea id="additional_notes" v-model="form.additional_notes" />
                            </div>
                        </div>
                    </section>

                    <!-- Submit -->
                    <div class="flex justify-end pt-4">
                        <Button type="submit" class="w-full md:w-auto px-8 py-2" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                            Create Book Record
                        </Button>
                    </div>
                </form>
            </div>
        </RecordsLayout>
    </AppLayout>
</template>
