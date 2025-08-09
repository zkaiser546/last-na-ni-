<script setup lang="ts">
import { Dialog, DialogContent, DialogTrigger } from '@/components/ui/dialog';

const getCoverUrl = (path: string) => {
    return path ? `/storage/uploads/book-covers/${path}` : `/storage/placeholders/sample1.png`;
};

defineProps({
    record: Object,
});

</script>

<template>
    <Dialog class="">
        <DialogTrigger as-child>
            <div class="flex gap-4 w-full">
                <div class="items-center flex">
                    <div class="font-medium leading-tight">{{ record.accession_number }}</div>
                </div>
                <div class="record-content w-full">
                    <div class="text-md font-semibold leading-tight truncate w-sm  0">{{ record.title }}</div>
                    <div class="flex justify-between">
                        <div class="text-sm text-gray-600 leading-tight">{{ record.book.authors }}</div>
                        <div class="text-sm text-gray-500 leading-tight">{{ record.book.publication_year }}</div>
                    </div>
                </div>
            </div>
        </DialogTrigger>
        <DialogContent class="grid gap-6 h-full max-h-9/10 sm:grid-cols-2 sm:max-w-6xl justify-between">
            <div class="flex items-center justify-center">
                <img
                    class="max-w-xs rounded-lg shadow-md"
                    loading="lazy"
                    :src="getCoverUrl(record?.book.cover_image)"
                    alt="Book Cover"
                />
            </div>

            <div class="space-y-6 overflow-y-auto">
                <div>
                    <h2 class="text-2xl font-bold">{{ record?.title }}</h2>
                    <p class="text-muted-foreground">{{ record?.accession_number }}</p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <span class="font-semibold">Authors:</span>
                        <p>{{ record?.book.authors }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">Editors:</span>
                        <p>{{ record?.book.editors }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">ISBN:</span>
                        <p>{{ record?.book.isbn }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">Publication Year:</span>
                        <p>{{ record?.book.publication_year }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">Call Number:</span>
                        <p>{{ record?.book.call_number }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">Publisher:</span>
                        <p>{{ record?.book.publisher }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">Status:</span>
                        <p>{{ record?.book.status }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">Volume:</span>
                        <p>{{ record?.book.volume }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">Publication Place:</span>
                        <p>{{ record?.book.publication_place }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">DDC Class ID:</span>
                        <p>{{ record?.book.ddc_class_id }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-semibold">Physical Location ID:</span>
                        <p>{{ record?.book.physical_location_id }}</p>
                    </div>
                </div>

                <div class="space-y-1">
                    <span class="font-semibold">Subject Headings:</span>
                    <p>{{ record?.subject_headings }}</p>
                </div>

                <div class="space-y-1">
                    <span class="font-semibold">Table of Contents:</span>
                    <p class="whitespace-pre-wrap">{{ record?.book.table_of_contents }}</p>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
