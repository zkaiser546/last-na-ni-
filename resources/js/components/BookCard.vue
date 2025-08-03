<script setup lang="ts">

import { Card, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const getCoverUrl = (path: string) => {
    return path ? `/storage/uploads/book-covers/${path}` : `/storage/placeholders/sample1.png`;
}

defineProps({
    record: Object,
});

</script>

<template>
    <Card class="w-[350px]">
        <CardHeader>
            <CardTitle>{{ record?.title }}</CardTitle>
            <CardDescription>Deploy your new project in one-click.</CardDescription>
            {{ record?.accession_number }}
            {{ record?.title }}
            isbn: {{ record?.book.isbn }}
            year: {{ record?.book.publication_year }}
            authors: {{ record?.book.authors }}
            cover: {{ record?.book.cover_image }}
            <img width="100" :src="getCoverUrl(record?.book.cover_image)" alt="Book Cover" />
        </CardHeader>
    </Card>
</template>

<style scoped>

</style>
