<script setup lang="ts">
import { Search } from "lucide-vue-next"
import { Input } from "@/components/ui/input"
import { router, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

const props = defineProps({
    search_result: Object,
    search_term: String,
    search_button: Boolean,
});

const form = useForm({
    search: props.search_term,
});

const search = () => {
    router.get(route('home'), { search: form.search, search_button: true }, { preserveState: true });
};

const clearSearch = () => {
    form.search = ''; // Clear the search input
    router.get(route('home'), { search: '' }, { preserveState: true }); // Update the route
};

</script>

<template>
    <form @submit.prevent="search" class="w-full max-w-md p-2">
        <div class="flex items-center gap-2">
            <!-- Search Input Container -->
            <div class="relative flex-1">
                <Input
                    required
                    v-model="form.search"
                    id="search"
                    type="search"
                    placeholder="Search accession, title..."
                    class="pl-10"
                    autocomplete="off"
                />
                <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                <Search class="size-6 text-muted-foreground" />
            </span>
            </div>

            <!-- Button Container -->
            <div class="flex gap-2 flex-shrink-0">
                <Button
                    type="submit"
                    @click="search"
                >
                    Search
                </Button>
                <Button
                    type="button"
                    variant="outline"
                    v-if="form.search"
                    @click="clearSearch"
                >
                    Clear
                </Button>
            </div>
        </div>
    </form>

    <div v-if="search_result?.data?.length" class="max-w-md">
        <div v-for="result in search_result?.data" :key="result.id" class="grid gap-y-4">
            {{ result.accession_number }}
            {{ result.title }}
            {{ result.book.authors }}
            {{ result.book.publication_year }}
        </div>
    </div>
    <div v-else-if="!search_result?.data?.length && search_button">
        No records found
    </div>
</template>
