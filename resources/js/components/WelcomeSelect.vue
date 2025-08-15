<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select"

// Define props and emits for v-model support
const props = defineProps<{
    modelValue?: string
}>()

const emit = defineEmits<{
    'update:modelValue': [value: string]
}>()

// Handle select value change
const handleValueChange = (value: string) => {
    emit('update:modelValue', value)
}

// Computed property for display text based on selected value
const getDisplayText = (value: string) => {
    const textMap: Record<string, string> = {
        'all': 'Search all',
        'books': 'Books',
        'multimedia': 'Multimedia',
        'magazines': 'Magazines/Periodicals',
        'thesis': 'Thesis/Dissertation'
    }
    return textMap[value] || 'Search all'
}
</script>

<template>
    <Select :model-value="modelValue" @update:model-value="handleValueChange">
        <SelectTrigger class="w-[180px]">
            <SelectValue :placeholder="getDisplayText(modelValue || 'all')" class="text-gray-900 dark:text-gray-100" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectLabel>Records</SelectLabel>
                <SelectItem value="all">
                    <span class="text-gray-900 dark:text-gray-100">All</span>
                </SelectItem>
                <SelectItem value="books">
                    Books
                </SelectItem>
                <SelectItem value="multimedia">
                    Multimedia
                </SelectItem>
                <SelectItem value="magazines">
                    Magazines/Periodicals
                </SelectItem>
                <SelectItem value="thesis">
                    Thesis/Dissertation
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
