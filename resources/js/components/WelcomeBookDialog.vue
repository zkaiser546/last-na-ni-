<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogTrigger,
} from "@/components/ui/dialog"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const getCoverUrl = (path: string) => {
    return path ? `/storage/uploads/book-covers/${path}` : `/storage/placeholders/sample1.png`;
}

defineProps({
    record: Object,
});

</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Card class="grid grid-cols-2">
                <CardHeader class="">
                    <CardTitle>{{ record?.title }}</CardTitle>
                    <CardDescription class="flex justify-between">
                        <div>{{ record?.accession_number }}</div>
                        <div>{{ record?.book.authors }}</div>
                        <div>{{ record?.book.publication_year }}</div>
                    </CardDescription>
                </CardHeader>
                <CardContent class="flex justify-end">
                    <img width="100" :src="getCoverUrl(record?.book.cover_image)" alt="Book Cover" />
                </CardContent>
            </Card>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">

                <CardHeader class="">
                    <CardTitle>{{ record?.title }}</CardTitle>
                    <CardDescription class="flex justify-between">
                        <div>{{ record?.accession_number }}</div>
                        <div>{{ record?.book.authors }}</div>
                    </CardDescription>
                </CardHeader>
                <CardContent class="flex justify-end">
                    <img width="100" :src="getCoverUrl(record?.book.cover_image)" alt="Book Cover" />
                </CardContent>

            <!--
        isbn: {{ record?.book.isbn }}
        year: {{ record?.book.publication_year }}
        cover: {{ record?.book.cover_image }}
        -->
        </DialogContent>
    </Dialog>
</template>
