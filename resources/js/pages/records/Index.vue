<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Define props interface
interface Records {
    last_page: number;
    data: any[]; // Add this
    current_page: number; // Add this
    prev_page_url: string | null; // Add this
    next_page_url: string | null; // Add this
}

interface Props {
    records: Records;
}

// Define props
const props = defineProps<Props>();

// Convert computed property
const totalPages = computed(() => {
    return props.records.last_page;
});

// Convert method
const goToPage = (page: number) => {
    router.get(
        route('records.index'),
        { page: page },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

</script>

<template>
    <Head title="Books" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <h1 class="text-4xl">FUNCTIONALITY lang ha</h1>

            <div class="flex gap-2">
                <Link :href="route('books.import')">
                    <Button variant="secondary">Initial Import Books</Button>
                </Link>
            </div>

            <div>
                <!-- Display Records -->
                <div class="records-container">
                    <h1>Records</h1>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <!-- Add other columns as needed -->
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="record in records.data" :key="record.id">
                            <td>{{ record.accession_number }}</td>
                            <td>{{ record.title }}</td>
                            <!-- Add other fields as needed -->
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div class="pagination">
                    <!-- Previous Button -->
                    <button
                        :disabled="!records.prev_page_url"
                        @click="goToPage(records.current_page - 1)"
                    >
                        Previous
                    </button>

                    <!-- Page Numbers -->
                    <button
                        v-for="page in totalPages"
                        :key="page"
                        :class="{ active: page === records.current_page }"
                        @click="goToPage(page)"
                    >
                        {{ page }}
                    </button>

                    <!-- Next Button -->
                    <button
                        :disabled="!records.next_page_url"
                        @click="goToPage(records.current_page + 1)"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
