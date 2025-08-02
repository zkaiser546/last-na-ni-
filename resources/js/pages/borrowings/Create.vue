<script setup lang="ts">
    import { Head, router, useForm } from '@inertiajs/vue3';
    import AppLayout from '@/layouts/AppLayout.vue';
    import type { BreadcrumbItem } from '@/types';
    import { Input } from '@/components/ui/input';
    import { LoaderCircle, Search } from 'lucide-vue-next';
    import { Button } from '@/components/ui/button';
    import { computed } from 'vue';
    import BookScannerDialog from '@/components/BookScannerDialog.vue';
    import BorrowTypeRadio from '@/components/BorrowTypeRadio.vue';

    const breadcrumbs: BreadcrumbItem[] = [
      {
          title: 'Borrowings',
          href: '/borrowings',
      },
      {
          title: 'Borrow/Return',
          href: '/borrow-return',
      },
    ];

    defineProps({
      search_ac_result: Object,
    });

    const form = useForm({
        searchAcc: "",
        borrowType: "inside", // Add default value
    });

    const searchAcc = () => {
        router.get(route('borrowings.create'), {searchAcc: form.searchAcc})
    }

    const clearSearch = () => {
        form.searchAcc = "";
        // Optional: Also clear any search results by navigating back to the base route
        router.get(route('borrowings.create'));
    }

    const searchedTerm = computed(() => {
        return new URLSearchParams(window.location.search).get('searchAcc') || ''
    })

    const searchAttempted = computed(() => {
        return searchedTerm.value !== ''
    })

</script>

<template>
    <Head title="Create Students" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <div class="grid grid-cols-2 gap-8">
                <div class="relative grid gap-2 w-full max-w-sm items-center">

                    <BorrowTypeRadio v-model="form.borrowType" />
                    <BookScannerDialog />

                    <form @submit.prevent="searchAcc">
                        <div class="relative">
                            <Input required id="search" type="text" placeholder="Search Accession Number..." class="pl-10"
                                   v-model="form.searchAcc"
                            />
                            <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                                <Search class="size-6 text-muted-foreground" />
                            </span>
                        </div>
                        <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            Find Book
                        </Button>
                        <Button variant="outline" class="mt-2 w-full" tabindex="6" :disabled="form.processing" @click="clearSearch">
                            Clear
                        </Button>
                    </form>
                </div>
                <div>
                    <div v-if="search_ac_result" class="search-result">
                        <h3>Record Found:</h3>
                        <p><strong>Accession Number:</strong> {{ search_ac_result.accession_number }}</p>
                        <p><strong>Title:</strong> {{ search_ac_result.title }}</p>
                        <!-- Add other fields as needed -->
                        <Button v-if="form.borrowType==='inside'">Borrow (Inside)</Button>
                        <Button v-else-if="form.borrowType==='take-home'">Borrow (Take Home)</Button>
                    </div>
                    <div v-else-if="searchAttempted && !search_ac_result" class="no-result">
                        <p>No record found with that accession number.</p>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
