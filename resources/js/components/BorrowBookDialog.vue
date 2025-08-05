<script setup lang="ts">
import { Button } from "@/components/ui/button"
import {
    Dialog,
    DialogContent,
    DialogTrigger,
} from "@/components/ui/dialog"

defineProps({
    user: Object,
    book: Number,
});
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
            {{ book }}
        </DialogContent>
    </Dialog>
</template>
