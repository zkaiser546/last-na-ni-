<script setup lang="ts">
    import { Head, router, useForm } from '@inertiajs/vue3';
    import AppLayout from '@/layouts/AppLayout.vue';
    import type { BreadcrumbItem } from '@/types';
    import { Input } from '@/components/ui/input';
    import { LoaderCircle, Search } from 'lucide-vue-next';
    import { Button } from '@/components/ui/button';
    import { computed } from 'vue';
    import BookScannerDialog from '@/components/BookScannerDialog.vue';

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

    const props = defineProps({
      search_ac_result: Object,
    });

    const form = useForm({
        searchAcc: "",
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

    interface BorrowFormData {
        accession_number: number | null
        borrow_type: 'inside' | 'take_home' | null
    }

    type BorrowType = 'inside' | 'take_home'

    const borrowForm = useForm<BorrowFormData>({
        accession_number: null,
        borrow_type: null
    })

    const borrow = (type: BorrowType): void => {
        if (!props.search_ac_result) return

        borrowForm.accession_number = props.search_ac_result.accession_number
        borrowForm.borrow_type = type
        borrowForm.post(route('borrowings.store'))
    }

</script>

<template>
    <Head title="Borrow/Return books" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <div class="grid grid-cols-2 gap-8">
                <div class="relative grid gap-2 w-full max-w-sm items-center">

                    <BookScannerDialog />

                    <form @submit.prevent="searchAcc">
                        <div class="relative">
                            <Input required id="search" type="number" placeholder="Search Accession Number..." class="pl-10"
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
                        <p><strong>Status:</strong> {{ search_ac_result.status }}</p>
                        <!-- Add other fields as needed -->
                        <div v-if="search_ac_result.status === 'available'" class="flex gap-2">
                            <Button @click="borrow('inside')" :disabled="borrowForm.processing">
                                Borrow (Inside)
                            </Button>
                            <Button @click="borrow('take_home')" :disabled="borrowForm.processing">
                                Borrow (Take Home)
                            </Button>
                        </div>
                    </div>
                    <div v-else-if="searchAttempted && !search_ac_result" class="no-result">
                        <p>No record found with that accession number.</p>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
