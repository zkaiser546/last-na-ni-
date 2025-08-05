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
    <form @submit.prevent="search" class="search-form">
        <div class="flex items-center gap-2">
            <!-- Search Input Container -->
            <div class="relative flex-1 search-input-container">
                <Input
                    required
                    v-model="form.search"
                    id="search"
                    type="search"
                    placeholder="Search accession, title..."
                    class="pl-10 search-input w-sm"
                    autocomplete="off"
                />
                <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2 search-icon">
                    <Search class="size-6 text-muted-foreground" />
                </span>
            </div>

            <!-- Button Container -->
            <div class="flex gap-2 flex-shrink-0 button-container">
                <Button
                    type="submit"
                    @click="search"
                    class="search-button"
                >
                    Search
                </Button>
                <Transition name="clear-button">
                    <Button
                        type="button"
                        variant="outline"
                        v-if="form.search"
                        @click="clearSearch"
                        class="clear-button"
                    >
                        Clear
                    </Button>
                </Transition>
            </div>
        </div>
    </form>

    <!-- Search Results -->
    <Transition name="fade" mode="out-in">
        <div v-if="search_result?.data?.length" class="max-w-md results-container">
            <TransitionGroup name="result-item" tag="div" class="grid gap-y-1">
                <div v-for="result in search_result?.data" :key="result.id" class="result-item">
                    <div class="flex gap-4 w-full">
                        <div class="items-center flex">
                            <div class="font-medium leading-tight">{{ result.accession_number }}</div>
                        </div>
                        <div class="result-content w-full">
                            <div class="text-md font-semibold leading-tight truncate">{{ result.title }}</div>
                            <div class="flex justify-between">
                                <div class="text-sm text-gray-600 leading-tight">{{ result.book.authors }}</div>
                                <div class="text-sm text-gray-500 leading-tight">{{ result.book.publication_year }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </TransitionGroup>
        </div>
        <div v-else-if="!search_result?.data?.length && search_button" class="no-results">
            <div class="text-center text-gray-500 py-4">
                <Search class="size-8 mx-auto mb-1 opacity-50" />
                <p>No records found</p>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
/* Form transitions */
.search-form {
    width: 100%;
    max-width: 60rem;
    padding: 0.5rem;
    transition: all 0.3s ease;
}

.search-input-container {
    transition: transform 0.2s ease;
}

.search-input-container:focus-within {
    transform: scale(1.02);
}

.search-input {
    transition: all 0.3s ease;
}

.search-input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-icon {
    transition: color 0.3s ease;
}

.search-input:focus + .search-icon {
    color: rgb(59, 130, 246);
}

/* Button transitions */
.button-container {
    transition: gap 0.3s ease;
}

.search-button,
.clear-button {
    transition: all 0.3s ease;
}

.search-button:hover,
.clear-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.search-button:active,
.clear-button:active {
    transform: translateY(0);
}

/* Clear button transition */
.clear-button-enter-active,
.clear-button-leave-active {
    transition: all 0.3s ease;
}

.clear-button-enter-from {
    opacity: 0;
    transform: translateX(10px) scale(0.9);
}

.clear-button-leave-to {
    opacity: 0;
    transform: translateX(10px) scale(0.9);
}

/* Results container transitions */
.results-container {
    transition: all 0.3s ease;
}

/* Fade transition for results/no results */
.fade-enter-active,
.fade-leave-active {
    transition: all 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(10px);
}

/* Individual result item transitions */
.result-item {
    transition: all 0.3s ease;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid rgb(229, 231, 235);
    background: white;
}

.result-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: rgb(209, 213, 219);
}

.result-content {
    transition: all 0.3s ease;
}

/* TransitionGroup animations for result items */
.result-item-enter-active {
    transition: all 0.5s ease;
}

.result-item-leave-active {
    transition: all 0.3s ease;
}

.result-item-enter-from {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
}

.result-item-leave-to {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
}

.result-item-move {
    transition: transform 0.3s ease;
}

/* No results transition */
.no-results {
    transition: all 0.4s ease;
}

/* Loading states (optional) */
.search-form:has(.search-input:focus) .search-button {
    background-color: rgb(59, 130, 246);
    color: white;
}

/* Responsive transitions */
@media (max-width: 640px) {
    .search-form {
        max-width: 100%;
        padding: 0.25rem;
    }

    .button-container {
        flex-direction: column;
        gap: 0.5rem;
    }

    .result-item:hover {
        transform: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
