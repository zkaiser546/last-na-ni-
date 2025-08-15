<script setup lang="ts">
import { ref, defineEmits } from 'vue';
import { Button } from '@/components/ui/button';

const open = ref(false);
const emit = defineEmits<{
    (e: 'select', type: string): void
}>();

function selectReport(type: string) {
    open.value = false;
    emit('select', type);
}
</script>

<template>
    <div class="relative inline-block text-left">
        <div class="flex">
            <Button class="rounded-r-none bg-usepmaroon text-white hover:bg-red-900">Statistical Data</Button>
            <Button
                class="rounded-l-none bg-usepmaroon text-white hover:bg-red-900"
                @click="open = !open"
                aria-haspopup="true"
                :aria-expanded="open"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </Button>
        </div>
        <div
            v-if="open"
            class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        >
            <div class="py-1">
                <button
                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-usepmaroon"
                    @click="selectReport('Comparative Report')"
                >Comparative Report</button>
                <button
                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-usepmaroon"
                    @click="selectReport('General Book Loan')"
                >General Book Loan</button>
                <button
                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-usepmaroon"
                    @click="selectReport('Graduate Book Loan')"
                >Graduate Book Loan</button>
            </div>
        </div>
    </div>
</template>
