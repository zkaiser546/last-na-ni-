<script setup lang="ts">
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from "@/components/ui/tags-input"
import { ref, watch } from "vue"

const props = defineProps<{
    modelValue: string[]
}>()
const emit = defineEmits<{
    (e: "update:modelValue", value: string[]): void
}>()

const localValue = ref([...props.modelValue])

watch(() => props.modelValue, (newVal) => {
    localValue.value = [...newVal]
})

watch(localValue, (newVal) => {
    emit("update:modelValue", newVal)
}, { deep: true })
</script>

<template>
    <TagsInput v-model="localValue">
        <TagsInputItem v-for="item in localValue" :key="item" :value="item">
            <TagsInputItemText />
            <TagsInputItemDelete />
        </TagsInputItem>

        <TagsInputInput placeholder="Subject Headings..." />
    </TagsInput>
</template>
