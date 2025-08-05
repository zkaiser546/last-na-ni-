<script setup lang="ts">
import { Button } from "@/components/ui/button"
import {
    Dialog,
    DialogContent,
    DialogTrigger,
} from "@/components/ui/dialog"
import { router } from '@inertiajs/vue3';

defineProps({
    user: Object,
    book_accession: String,
});

const borrowBook = (user_id, book_accession) => {
    const credentials = {
        user_id: user_id,
        book_accession: book_accession
    };

    router.post(route('borrowings.borrow', credentials))
};

</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="ghost" class="w-full text-left">
                {{ user.label }} ({{ user.email }})
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            {{ user.fullName  }}
            {{ book_accession ? book_accession : 'No book selected' }}

            <Button @click="borrowBook()" v-if="user.fullName && book_accession">Borrow Book</Button>
        </DialogContent>
    </Dialog>
</template>
