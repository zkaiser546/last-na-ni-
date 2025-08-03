<script setup lang="ts">
import { Search } from "lucide-vue-next"
import { Input } from "@/components/ui/input"
import { router, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

const props = defineProps({
    search_result: Object,
    search_term: String,
});

const form = useForm({
    search: props.search_term,
});

const search = () => {
    router.get(route('home'), { search: form.search }, { preserveState: true });
};

const clearSearch = () => {
    form.search = ''; // Clear the search input
    router.get(route('home'), { search: '' }, { preserveState: true }); // Update the route
};
</script>

<template>
    <div class="relative w-full max-w-sm items-center">
        <form @submit.prevent="search">
            <Input v-model="form.search" id="search" type="search"
                   placeholder="Search accession, title..." class="pl-10" />
        </form>
        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
          <Search class="size-6 text-muted-foreground" />
        </span>
    </div>
    <Button v-if="form.search" @click="clearSearch">Clear</Button>
    <div v-if="search_result?.data?.length" class="max-w-md">
        <div v-for="result in search_result?.data" :key="result.id" class="grid gap-y-4">
            {{ result.accession_number }}
            {{ result.title }}
            {{ result.book.authors }}
            {{ result.book.publication_year }}
        </div>
    </div>
    <div v-else>
        No records found
    </div>
</template>
