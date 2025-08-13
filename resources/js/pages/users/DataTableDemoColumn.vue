<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'

const props = defineProps({
    user: Object,
    onEdit: Function,
    onDelete: Function, // Add onDelete prop
})

const edit = (id) => {
    if (props.onEdit) {
        props.onEdit(id);
    }
}

const deleteItem = (id) => {
    if (props.onDelete) {
        props.onDelete(id);
    }
}
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="w-8 h-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="w-4 h-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>
            <DropdownMenuItem @click="edit(user.id)">
                Edit
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem @click="deleteItem(user.id)" class="text-red-600 focus:text-red-600">
                Delete
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
