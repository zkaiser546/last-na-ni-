<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { ScanQrCode } from 'lucide-vue-next'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'

</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button>
                <ScanQrCode class="w-4 h-4 mr-2" /> Scan Book
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Edit profile</DialogTitle>
                <DialogDescription>
                    Make changes to your profile here. Click save when you're done.
                </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                Video Feed here
            </div>
            <DialogFooter>
                <Button type="submit">
                    Save changes
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
