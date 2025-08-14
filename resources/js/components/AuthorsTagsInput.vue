<script setup lang="ts">
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from "@/components/ui/tags-input"
import { ref, watch } from "vue"

// Props and emits
const props = defineProps<{
    modelValue: string[]
}>()
const emit = defineEmits<{
    (e: "update:modelValue", value: string[]): void
}>()

// Internal state synced with parent
const localValue = ref([...props.modelValue])

// Watch for parent updates
watch(() => props.modelValue, (newVal) => {
    localValue.value = [...newVal]
})

// Watch for local changes to emit back to parent
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

        <TagsInputInput placeholder="Authors..." />
    </TagsInput>
</template>
