<script setup lang="ts">
import { Button } from "@/components/ui/button"
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog"
import { Label } from "@/components/ui/label"
import {  ref, watch } from 'vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue
} from '@/components/ui/select';

const props = defineProps({
    patron: Object,
    purposes: Object,
});

const open = ref(false)

// Watch for patron changes and open dialog
watch(() => props.patron, (newPatron) => {
    if (newPatron) {
        open.value = true
    }
}, { immediate: true })

</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child class="hidden">
            <Button
                ref="triggerRef"
                variant="outline"
            >
                Edit Profile
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle>Welcome back {{ patron.first_name }}!</DialogTitle>
                <DialogDescription>
                    What transaction would you do today?
                </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="name" class="text-right">
                        Purpose
                    </Label>

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
                </div>
            </div>
            <DialogFooter>
                <Button type="submit">
                    Save changes
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
