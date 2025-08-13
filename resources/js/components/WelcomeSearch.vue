<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { Search } from "lucide-vue-next";
import { Input } from "@/components/ui/input";
import { router } from '@inertiajs/vue3';
import WelcomeSelect from '@/components/WelcomeSelect.vue';
import WelcomeSearchDialog from '@/components/WelcomeSearchDialog.vue';

const props = defineProps<{
    search_result?: any;
    search_term?: string;
    record_type?: string;
}>();

const searchQuery = ref(props.search_term || '');
const recordType = ref(props.record_type || 'all');
const suggestions = ref<any[]>([]);
const showSuggestions = ref(false);
const debounceTimer = ref<ReturnType<typeof setTimeout> | null>(null);
const searchContainer = ref<HTMLElement | null>(null);

const fetchSuggestions = () => {
    const query = searchQuery.value.trim();
    if (!query) {
        suggestions.value = [];
        showSuggestions.value = false;
        return;
    }

    router.get(route('home'), {
        search: query,
        record_type: recordType.value,
        search_button: false
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            suggestions.value = page.props.search_result?.data || [];
            showSuggestions.value = true;
            console.log('Search results:', suggestions.value); // Debug log
        }
    });
};

// Watch searchQuery for changes with debounce
watch(searchQuery, () => {
    if (debounceTimer.value) {
        clearTimeout(debounceTimer.value);
    }
    debounceTimer.value = setTimeout(fetchSuggestions, 300);
});

// Record type change triggers search
const onRecordTypeChange = (value: string) => {
    recordType.value = value;
    if (searchQuery.value) {
        fetchSuggestions();
    }
};

// Click-outside handler
const handleClickOutside = (event: MouseEvent) => {
    if (searchContainer.value && !searchContainer.value.contains(event.target as Node)) {
        showSuggestions.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    // If there's an initial search term, fetch results
    if (props.search_term) {
        fetchSuggestions();
    }
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="searchContainer" class="relative w-full max-w-3xl">
        <div class="flex items-center gap-2">
            <WelcomeSelect
                :model-value="recordType"
                @update:model-value="onRecordTypeChange"
            />
            <!-- Search Input -->
            <div class="relative flex-1">
                <Input
                    v-model="searchQuery"
                    id="search"
                    type="search"
                    placeholder="Search accession, title..."
                    class="pl-10 w-full"
                    @focus="searchQuery && (showSuggestions = true)"
                />
                <span class="absolute start-0 inset-y-0 flex items-center px-2">
                    <Search class="size-6 text-muted-foreground" />
                </span>

                <!-- Suggestions dropdown -->
                <div
                    v-if="showSuggestions && suggestions.length > 0"
                    class="absolute z-50 mt-1 w-full bg-white border rounded-lg shadow-lg max-h-[60vh] overflow-y-auto"
                >
                    <div
                        v-for="result in suggestions"
                        :key="result.id"
                        class="p-2 hover:bg-gray-50"
                    >
                        <WelcomeSearchDialog :record="result" />
                    </div>
                </div>

                <!-- No results message -->
                <div
                    v-if="showSuggestions && searchQuery && !suggestions.length"
                    class="absolute z-50 mt-1 w-full bg-white border rounded-lg shadow-lg p-4 text-center text-gray-500"
                >
                    No results found
                </div>
            </div>
        </div>
    </div>
</template>
