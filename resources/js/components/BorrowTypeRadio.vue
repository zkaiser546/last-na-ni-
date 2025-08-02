<script setup lang="ts">
import { Label } from '@/components/ui/label'
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group'
import { computed } from 'vue'

const props = defineProps<{
    modelValue?: string
}>()

const emit = defineEmits<{
    'update:modelValue': [value: string]
}>()

// Create a computed property for two-way binding with the RadioGroup
const borrowType = computed({
    get: () => props.modelValue || 'inside', // default to 'inside'
    set: (value: string) => emit('update:modelValue', value)
})
</script>

<template>
    <RadioGroup v-model="borrowType" default-value="inside" class="flex">
        <div class="flex items-center space-x-2">
            <RadioGroupItem id="option-one" value="inside" />
            <Label for="option-one">Inside</Label>
        </div>
        <div class="flex items-center space-x-2">
            <RadioGroupItem id="option-two" value="take-home" />
            <Label for="option-two">Take Home</Label>
        </div>
    </RadioGroup>
</template>
