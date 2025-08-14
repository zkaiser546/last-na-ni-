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
    ddcClassifications: { id: number; code: string; name: string }[];
    lcClassifications: { id: number; code: string; name: string }[];
    physicalLocations: { id: number; name: string }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Records', href: '/records' },
    { title: 'Books', href: '/records/books' },
    { title: 'Create Book', href: '/records/books/create' },
];

const form = useForm({
    accession_number: '',
    title: '',
    authors: [],
    editors: [],
    publication_year: '',
    publisher: '',
    publication_place: '',
    isbn: '',
    call_number: '',
    ddc_class_id: '',
    lc_class_id: '',
    physical_location_id: '',
    cover_image: null,
    ics_number: '',
    ics_date: '',
    pr_number: '',
    pr_date: '',
    po_number: '',
    po_date: '',
    source: '',
    purchase_amount: '',
    lot_cost: '',
    supplier: '',
    donated_by: '',
    cover_type: '',
    condition: '',
    table_of_contents: '',
    subject_headings: [],
});

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
                                    <span class="text-sm text-gray-500">(Hit 'enter' for multiple authors)</span>
                                </div>
                                <AuthorsTagsInput v-model="form.authors" />
                                <InputError :message="form.errors.authors" />
                            </div>
                            <div class="grid gap-2">
                                <div class="flex gap-2">
                                    <Label for="editors">Editor/s</Label>
                                    <span class="text-sm text-gray-500">(Hit 'enter' for multiple editors)</span>
                                </div>
                                <EditorsTagsInput v-model="form.editors" />
                                <InputError :message="form.errors.editors" />
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
                                <Label for="publication_place">Publication Place</Label>
                                <Input id="publication_place" type="text" required v-model="form.publication_place" />
                                <InputError :message="form.errors.publication_place" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="isbn">ISBN</Label>
                                <Input id="isbn" type="text" required v-model="form.isbn" />
                                <InputError :message="form.errors.isbn" />
                            </div>
                        </div>
                    </section>

                    <!-- Classification & Location -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold">Classification & Location</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="grid gap-2">
                                <Label for="call_number">Call Number</Label>
                                <Input id="call_number" type="text" v-model="form.call_number" />
                            </div>
                            <!-- DDC Classification -->
                            <div v-if="!form.lc_class_id" class="grid gap-2">
                                <Label for="ddc_class_id">DDC Classification</Label>
                                <Select v-model="form.ddc_class_id">
                                    <SelectTrigger id="ddc_class_id">
                                        <SelectValue placeholder="Select DDC classification" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="ddc in props.ddcClassifications"
                                            :key="ddc.id"
                                            :value="ddc.id.toString()"
                                        >
                                            {{ ddc.code }} – {{ ddc.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <!-- LC Classification -->
                            <div v-if="!form.ddc_class_id" class="grid gap-2">
                                <Label for="lc_class_id">LC Classification</Label>
                                <Select v-model="form.lc_class_id">
                                    <SelectTrigger id="lc_class_id">
                                        <SelectValue placeholder="Select LC classification" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="lc in props.lcClassifications"
                                            :key="lc.id"
                                            :value="lc.id.toString()"
                                        >
                                            {{ lc.code }} – {{ lc.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <!-- Physical Location -->
                            <div class="grid gap-2">
                                <Label for="physical_location_id">Physical Location</Label>
                                <Select v-model="form.physical_location_id" required>
                                    <SelectTrigger id="physical_location_id">
                                        <SelectValue placeholder="Select location" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {{ console.log(physicalLocations) }}
                                        <SelectItem
                                            v-for="loc in props.physicalLocations"
                                            :key="loc.id"
                                            :value="loc.id.toString()"
                                        >
                                            {{ loc.symbol }} - {{ loc.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </section>

                    <!-- Physical Description -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold">Physical Description</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="grid gap-2">
                                <Label for="cover_image">Cover Image</Label>
                                <Input id="cover_image" type="file" @change="e => form.cover_image = e.target.files[0]" />
                            </div>
                        </div>
                    </section>

                    <!-- Administrative Information -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold">Administrative Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- ICS -->
                            <div class="grid gap-2">
                                <Label for="ics_number">ICS Number</Label>
                                <Input id="ics_number" type="number" v-model="form.ics_number" />
                            </div>
                            <div v-if="form.ics_number" class="grid gap-2">
                                <Label for="ics_date">ICS Date</Label>
                                <Input id="ics_date" type="date" v-model="form.ics_date" />
                            </div>

                            <!-- PR -->
                            <div class="grid gap-2">
                                <Label for="pr_number">PR Number</Label>
                                <Input id="pr_number" type="number" v-model="form.pr_number" />
                            </div>
                            <div v-if="form.pr_number" class="grid gap-2">
                                <Label for="pr_date">PR Date</Label>
                                <Input id="pr_date" type="date" v-model="form.pr_date" />
                            </div>

                            <!-- PO -->
                            <div class="grid gap-2">
                                <Label for="po_number">PO Number</Label>
                                <Input id="po_number" type="number" v-model="form.po_number" />
                            </div>
                            <div v-if="form.po_number" class="grid gap-2">
                                <Label for="po_date">PO Date</Label>
                                <Input id="po_date" type="date" v-model="form.po_date" />
                            </div>

                            <!-- Source -->
                            <div class="grid gap-2">
                                <Label for="source">Source</Label>
                                <Select v-model="form.source" required>
                                    <SelectTrigger id="source">
                                        <SelectValue placeholder="Select source" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="purchase">Purchase</SelectItem>
                                        <SelectItem value="donation">Donation</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <!-- Purchase-specific -->
                            <template v-if="form.source === 'purchase'">
                                <div class="grid gap-2">
                                    <Label for="purchase_amount">Purchase Amount</Label>
                                    <Input id="purchase_amount" type="number" step="0.01" v-model="form.purchase_amount" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="lot_cost">Lot Cost</Label>
                                    <Input id="lot_cost" type="number" step="0.01" v-model="form.lot_cost" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="supplier">Supplier</Label>
                                    <Input id="supplier" type="text" v-model="form.supplier" />
                                </div>
                            </template>

                            <!-- Donation-specific -->
                            <template v-if="form.source === 'donation'">
                                <div class="grid gap-2">
                                    <Label for="donated_by">Donated By</Label>
                                    <Input id="donated_by" type="text" v-model="form.donated_by" />
                                </div>
                            </template>

                            <!-- Shared fields -->
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
                        </div>
                    </section>

                    <!-- Content Description -->
                    <section class="space-y-6">
                        <h2 class="text-lg font-semibold">Content Description</h2>
                        <div class="grid gap-6">
                            <div class="grid gap-2">
                                <Label for="table_of_contents">Table of Contents</Label>
                                <Textarea id="table_of_contents" rows="4" v-model="form.table_of_contents" />
                            </div>
                            <div class="grid gap-2">
                                <Label>Subject Headings</Label>
                                <SubjectTagsInput v-model="form.subject_headings" />
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
