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

defineProps({
    purposes: Object
});

</script>

<template>
    <Select>
        <SelectTrigger class="w-[280px]">
            <SelectValue placeholder="Select a fruit" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup >
                <SelectLabel>Purpose</SelectLabel>
                <SelectItem v-for="purpose in purposes" :key="purpose.id" value="purpose.id">
                    {{ purpose.name }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
</template>
