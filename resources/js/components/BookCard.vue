<script setup lang="ts">

import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const getCoverUrl = (path: string) => {
    return path ? `/storage/uploads/book-covers/${path}` : `/storage/placeholders/sample1.png`;
}

defineProps({
    record: Object,
});

</script>

<template>
    <Card class="grid grid-cols-2">
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
    </Card>
    <!--
            {{ record?.title }}
            isbn: {{ record?.book.isbn }}
            year: {{ record?.book.publication_year }}
            cover: {{ record?.book.cover_image }}
            -->
</template>

<style scoped>

</style>
