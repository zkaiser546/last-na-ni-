<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Input } from '@/components/ui/input';
import { LoaderCircle, Search } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { computed, ref } from 'vue';
import BookScannerDialog from '@/components/BookScannerDialog.vue';
import { Dialog, DialogContent, DialogOverlay } from '@/components/ui/dialog';

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
    patron: Object
});

const form = useForm({
    searchAcc: "",
});

const searchAcc = () => {
    router.get(route('borrowings.create'), {searchAcc: form.searchAcc})
}

const clearSearch = () => {
    form.searchAcc = "";
    router.get(route('borrowings.create'));
}

const searchedTerm = computed(() => {
    return new URLSearchParams(window.location.search).get('searchAcc') || ''
})

interface BorrowFormData {
    [key: string]: any;
    accession_number: number | null;
    borrow_type: 'inside' | 'take-home' | null;
    searchPatron: string;
    user_id: number | null;
    patron_avatar_url?: string;
}

type BorrowType = 'inside' | 'take-home'

const borrowForm = useForm<BorrowFormData>({
    accession_number: null,
    borrow_type: null,
    searchPatron: '',
    user_id: null
})

const borrow = (type: BorrowType): void => {
    if (!props.search_ac_result || !borrowForm.user_id) {
        console.log('Missing required data:', {
            book: props.search_ac_result,
            userId: borrowForm.user_id
        });
        return;
    }
    console.log('Sending borrow request:', {
        accession_number: props.search_ac_result.accession_number,
        borrow_type: type,
        user_id: borrowForm.user_id
    });
    borrowForm.accession_number = props.search_ac_result.accession_number;
    borrowForm.borrow_type = type;
    borrowForm.post(route('borrowings.store'));
}

const showScanModal = ref(false);
const showMissingIdModal = ref(false);

function openScanModal() {
    showScanModal.value = true;
    setTimeout(() => {
        const input = document.getElementById('searchPatron') as HTMLInputElement;
        if (input) input.focus();
    }, 300);
}

function closeScanModal() {
    showScanModal.value = false;
}

const searchPatron = () => {
    if (!borrowForm.searchPatron) {
        showMissingIdModal.value = true;
        return;
    }

    // Use preserve-state to keep the current book search results
    router.get(
        route('borrowings.create'),
        { searchPatron: borrowForm.searchPatron }, // <-- FIXED: use correct param name
        {
            preserveState: true,
            preserveScroll: true,
            only: ['patron', 'flash']
        }
    );
}

function handlePatronSubmit(e: Event) {
    e.preventDefault();
    searchPatron();
}

// USB barcode/RFID scanner handling
if (typeof window !== 'undefined') {
    let buffer = '';
    let lastInputTime = Date.now();
    window.addEventListener('keydown', (e) => {
        const now = Date.now();
        if (now - lastInputTime > 100) buffer = '';
        lastInputTime = now;
        if (/^[0-9]$/.test(e.key)) {
            buffer += e.key;
        } else if (e.key === 'Enter' && buffer.length > 3) {
            const input = document.getElementById('searchPatron') as HTMLInputElement;
            if (input) {
                input.value = buffer;
                input.dispatchEvent(new Event('input'));
            }
            buffer = '';
            showScanModal.value = false;
        }
    });
}

// Watch for patron changes and update user_id in the form
import { watch } from 'vue';
watch(() => props.patron, (newPatron) => {
    borrowForm.user_id = newPatron?.id || null;
});
</script>

<template>
    <Head title="Borrow/Return Books" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <!-- Back Button -->
            <div class="mb-2">
                <Button variant="outline" @click="$inertia.visit('/borrowings')" class="flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Back
                </Button>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <!-- Book Search and Result Section -->
                <div class="flex flex-col gap-4 w-full">
                    <!-- Book Result Card with Cover -->
                    <div class="search-result rounded-lg border p-4 flex flex-col md:flex-row gap-4 items-center bg-white shadow-sm">
                        <div class="w-28 h-40 bg-gray-100 flex items-center justify-center rounded overflow-hidden border flex-shrink-0">
                            <img v-if="search_ac_result && search_ac_result.cover_url" :src="search_ac_result.cover_url" alt="Book Cover" class="object-cover w-full h-full" />
                            <span v-else class="text-gray-400 text-xs">No Cover</span>
                        </div>
                        <div class="flex-1 w-full md:w-auto space-y-2 md:pr-4">
                            <h3 class="text-lg font-semibold mb-2 text-usepmaroon flex items-center gap-2">
                                <i class="fas fa-book"></i> Book Information
                            </h3>
                            <div class="grid grid-cols-1 gap-x-6 gap-y-1">
                                <p class="mb-2">
                                    <strong>Accession Number:</strong>
                                    <span v-if="search_ac_result" class="text-muted-foreground ml-2">
                                        {{ search_ac_result.accession_number }}
                                    </span>
                                    <span v-else class="text-muted-foreground ml-2">
                                        Enter accession number below
                                    </span>
                                </p>
                                <p class="mb-2">
                                    <strong>Title:</strong>
                                    <span v-if="search_ac_result" class="text-muted-foreground ml-2">
                                        {{ search_ac_result.title || 'N/A' }}
                                    </span>
                                    <span v-else class="text-muted-foreground ml-2">
                                        Book title will appear here
                                    </span>
                                </p>
                                <p>
                                    <strong>Status:</strong>
                                    <span v-if="search_ac_result" :class="{'text-green-600': search_ac_result.status === 'available', 'text-red-600': search_ac_result.status !== 'available'}" class="ml-2">
                                        {{ search_ac_result.status || 'N/A' }}
                                    </span>
                                    <span v-else class="text-muted-foreground ml-2">
                                        Availability status will appear here
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end justify-end h-full w-full md:w-auto md:pl-4">
                            <Button v-if="search_ac_result"
                                    @click="borrow('inside')"
                                    :disabled="borrowForm.processing || !search_ac_result || search_ac_result.status !== 'available'"
                                    class="w-full md:w-36 mt-4 md:mt-0 bg-usepmaroon text-white hover:bg-usepmaroon/90 shadow-md"
                            >
                                <i class="fas fa-sign-in-alt mr-2"></i> Borrow (Inside)
                            </Button>
                        </div>
                    </div>
                    <!-- Search Form -->
                    <BookScannerDialog />
                    <form @submit.prevent="searchAcc" class="flex flex-col gap-3">
                        <div class="relative">
                            <Input
                                required
                                id="search"
                                type="number"
                                placeholder="Search Accession Number..."
                                class="pl-10"
                                v-model="form.searchAcc"
                            />
                            <span class="absolute left-0 inset-y-0 flex items-center justify-center px-2">
                                <Search class="size-6 text-muted-foreground" />
                            </span>
                        </div>
                        <div class="flex gap-3">
                            <Button
                                variant="outline"
                                class="flex-1"
                                tabindex="6"
                                :disabled="form.processing"
                                @click="clearSearch"
                            >
                                Clear
                            </Button>
                            <Button
                                type="submit"
                                class="flex-1"
                                tabindex="5"
                                :disabled="form.processing"
                            >
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                <span v-else>Find Book</span>
                            </Button>
                        </div>
                    </form>
                </div>

                <!-- Patron Search and Result Section -->
                <div class="flex flex-col gap-4">
                    <!-- Patron Result Card with Avatar -->
                    <div class="search-result rounded-lg border p-4 flex flex-col md:flex-row gap-4 items-center bg-white shadow-sm">
                        <div class="w-20 h-20 bg-gray-100 flex items-center justify-center rounded-full overflow-hidden border flex-shrink-0">
                            <img v-if="borrowForm.patron_avatar_url" :src="borrowForm.patron_avatar_url" alt="Patron Avatar" class="object-cover w-full h-full" />
                            <span v-else class="text-gray-400 text-xs"><i class="fas fa-user fa-2x"></i></span>
                        </div>
                        <div class="flex-1 w-full md:w-auto space-y-2 md:pr-4">
                            <h3 class="text-lg font-semibold mb-2 text-usepmaroon flex items-center gap-2">
                                <i class="fas fa-user"></i> Patron Information
                            </h3>
                            <div class="grid grid-cols-1 gap-x-6 gap-y-1">
                                <p class="mb-2">
                                    <strong>Library ID:</strong>
                                    <span v-if="patron" class="text-muted-foreground ml-2">
                                        {{ patron.library_id }}
                                    </span>
                                    <span v-else class="text-muted-foreground ml-2">
                                        Enter library ID below
                                    </span>
                                </p>
                                <p class="mb-2">
                                    <strong>Name:</strong>
                                    <span v-if="patron" class="text-muted-foreground ml-2">
                                        {{ patron.first_name }} {{ patron.last_name }}
                                    </span>
                                    <span v-else class="text-muted-foreground ml-2">
                                        Patron name will appear here
                                    </span>
                                </p>
                                <p>
                                    <strong>Status:</strong>
                                    <span v-if="patron" class="text-muted-foreground ml-2">
                                        {{ patron.status || 'Active' }}
                                    </span>
                                    <span v-else class="text-muted-foreground ml-2">
                                        Patron status will appear here
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Patron Search Form (shown only when book is available) -->
                    <div v-if="search_ac_result && search_ac_result.status === 'available'" class="flex flex-col gap-4">
                        <form @submit="handlePatronSubmit" class="flex flex-col gap-3">
                            <div class="relative flex items-center gap-2">
                                <Input
                                    required
                                    id="searchPatron"
                                    type="number"
                                    placeholder="Search Library ID..."
                                    class="pl-10 flex-1"
                                    v-model="borrowForm.searchPatron"
                                />
                                <span class="absolute left-0 inset-y-0 flex items-center justify-center px-2">
                                    <Search class="size-6 text-muted-foreground" />
                                </span>
                                <Button type="button" variant="ghost" class="ml-2" title="Scan Barcode or RFID" @click="openScanModal()">
                                    <i class="fas fa-barcode text-lg"></i>
                                    <i class="fas fa-id-card text-lg ml-1"></i>
                                    <span class="ml-2 text-xs hidden md:inline">Scan</span>
                                </Button>
                            </div>
                            <div class="flex gap-3">
                                <Button
                                    variant="outline"
                                    class="flex-1"
                                    tabindex="6"
                                    :disabled="borrowForm.processing"
                                    @click="clearSearch"
                                >
                                    Clear
                                </Button>
                                <Button
                                    type="submit"
                                    class="flex-1"
                                    tabindex="5"
                                    :disabled="borrowForm.processing"
                                >
                                    <LoaderCircle v-if="borrowForm.processing" class="h-4 w-4 animate-spin" />
                                    <span v-else>Find Patron</span>
                                </Button>
                            </div>
                        </form>
                        <!-- Borrow Buttons -->
                        <Button
                            v-if="patron && patron.library_id && patron.first_name && patron.last_name"
                            @click="borrow('take-home')"
                            :disabled="borrowForm.processing"
                            class="w-full mt-2"
                        >
                            Borrow (Take Home)
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Scanner Modal -->
    <Dialog v-model:open="showScanModal">
        <DialogOverlay />
        <DialogContent class="max-w-sm mx-auto text-center">
            <div class="py-6">
                <div class="flex flex-col items-center mb-2">
                    <i class="fas fa-barcode text-5xl text-gray-700 mb-2"></i>
                    <i class="fas fa-id-card text-5xl text-gray-700 mb-2"></i>
                    <span class="text-xs text-gray-500">Barcode or RFID Scanner</span>
                </div>
                <h2 class="text-xl font-bold mb-2">Scan Barcode or RFID</h2>
                <p class="text-gray-600 mb-4">You can scan now. Please present the barcode or RFID card to the scanner. The input will be automatically filled.</p>
                <Button @click="closeScanModal" class="mt-2 w-full">Close</Button>
            </div>
        </DialogContent>
    </Dialog>

    <!-- Missing ID Modal -->
    <Dialog v-model:open="showMissingIdModal">
        <DialogOverlay />
        <DialogContent class="max-w-sm mx-auto text-center">
            <div class="py-6">
                <div class="flex flex-col items-center mb-2">
                    <i class="fas fa-id-card text-5xl text-gray-700 mb-2"></i>
                </div>
                <h2 class="text-xl font-bold mb-2">Please scan or enter student ID</h2>
                <p class="text-gray-600 mb-4">A student ID is required to proceed. Please scan the barcode/RFID or enter the ID manually.</p>
                <Button @click="showMissingIdModal = false" class="mt-2 w-full">Close</Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
