<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
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
            <Card class="overflow-hidden p-0">
                <div class="flex">
                    <!-- Main content area -->
                    <div class="flex-1 p-6">
                        <CardHeader class="p-0 pb-4">
                            <CardTitle class="line-clamp-2 text-xl font-semibold">
                                {{ record?.title }}
                            </CardTitle>
                            <div class="mt-2 text-sm text-muted-foreground">
                                {{ record?.book.authors }}
                            </div>
                        </CardHeader>

                        <CardContent class="p-0">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="space-y-1">
                                    <span class="text-muted-foreground">Accession Number</span>
                                    <div class="font-medium">{{ record?.accession_number }}</div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-muted-foreground">Year</span>
                                    <div class="font-medium">{{ record?.book.publication_year }}</div>
                                </div>
                            </div>

                            <div class="mt-4 border-t pt-4">
                                <div class="grid grid-cols-2 items-center gap-4 text-sm">
                                    <div class="flex justify-start">
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                            :class="{
                                                'bg-green-100 text-green-800': record?.status === 'Available',
                                                'bg-red-100 text-red-800': record?.status === 'Checked Out',
                                                'bg-yellow-100 text-yellow-800': record?.status === 'Reserved',
                                                'bg-gray-100 text-gray-800':
                                                    !record?.status || !['Available', 'Checked Out', 'Reserved'].includes(record?.status),
                                            }"
                                        >
                                            {{ record?.status }}
                                        </span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="text-xs text-muted-foreground">Copies</span>
                                        <span class="font-medium">{{ record?.copy_count }}</span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </div>

                    <!-- Book cover area -->
                    <div class="flex w-32 items-center justify-center bg-gray-50 p-4">
                        <img
                            class="h-auto max-h-40 w-full rounded-lg object-cover shadow-sm"
                            :src="getCoverUrl(record?.book.cover_image)"
                            :alt="`Cover of ${record?.title}`"
                            @error="(e) => e?.target && (e.target.style.display = 'none')"
                        />
                    </div>
                </div>
            </Card>
        </DialogTrigger>
        <DialogContent class="grid grid-cols-2 sm:max-w-4xl">
            <CardContent class="flex justify-end">
                <img width="300" loading="lazy" :src="getCoverUrl(record?.book.cover_image)" alt="Book Cover" />
            </CardContent>

            <CardHeader class="">
                <CardTitle>{{ record?.title }}</CardTitle>
                <CardDescription class="flex flex-col gap-1">
                    <div>{{ record?.accession_number }}</div>
                    <div>{{ record?.subject_headings }}</div>
                    <div>{{ record?.book.authors }}</div>
                    <div>{{ record?.book.editors }}</div>
                    <div>ISBN: {{ record?.book.isbn }}</div>
                    <div>Year: {{ record?.book.publication_year }}</div>
                    <div>Call number: {{ record?.book.call_number }}</div>
                    <div>Publisher: {{ record?.book.publisher }}</div>
                    <div>Status: {{ record?.book.status }}</div>
                    <div>Volume: {{ record?.book.volume }}</div>
                    <div>Publication place: {{ record?.book.publication_place }}</div>
                    <div>DDC class ID: {{ record?.book.ddc_class_id }}</div>
                    <div>Physical location ID: {{ record?.book.physical_location_id }}</div>
                    <div>Table of contents: {{ record?.book.table_of_contents }}</div>
                </CardDescription>
            </CardHeader>

        </DialogContent>
    </Dialog>
</template>
