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
</script>

<template>
    <Select>
        <SelectTrigger class="w-[180px]">
            <SelectValue placeholder="Search all" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectLabel>Records</SelectLabel>
                <SelectItem value="all">
                    All
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
