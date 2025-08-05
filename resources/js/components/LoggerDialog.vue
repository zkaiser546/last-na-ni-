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
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
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
const selectedPurpose = ref('')

// Create Inertia form
const form = useForm({
    patron_id: null,
    purpose_id: null,
})

// Watch for patron changes and open dialog
watch(() => props.patron, (newPatron) => {
    if (newPatron) {
        form.patron_id = newPatron.id
        open.value = true
    }
}, { immediate: true })

// Handle purpose selection
const handlePurposeChange = (value: string) => {
    selectedPurpose.value = value
    form.purpose_id = value
}

// Submit form
const submitForm = () => {
    form.post(route('logger.store'), {
        onSuccess: () => {
            open.value = false
            // Reset form or handle success
            form.reset()
            selectedPurpose.value = ''
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors)
        }
    })
}

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
            <form @submit.prevent="submitForm">
                <div class="grid gap-4 py-4">
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="purpose" class="text-right">
                            Purpose
                        </Label>

                        <Select v-model="selectedPurpose" @update:modelValue="handlePurposeChange">
                            <SelectTrigger class="w-[280px]">
                                <SelectValue placeholder="Select a purpose" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Purpose</SelectLabel>
                                    <SelectItem
                                        v-for="purpose in purposes"
                                        :key="purpose.id"
                                        :value="purpose.id.toString()"
                                    >
                                        {{ purpose.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- Show validation errors if any -->
                    <div v-if="form.errors.purpose_id" class="text-red-500 text-sm">
                        {{ form.errors.purpose_id }}
                    </div>
                </div>
                <DialogFooter>
                    <Button
                        type="submit"
                        :disabled="form.processing || !selectedPurpose"
                    >
                        <span v-if="form.processing">Processing...</span>
                        <span v-else>Save changes</span>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
