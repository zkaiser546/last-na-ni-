<template>
  <Dialog :open="open" @update:open="$emit('update:open', $event)">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>Book Created Successfully!</DialogTitle>
        <DialogDescription>
          Your book record has been created successfully.
        </DialogDescription>
      </DialogHeader>

      <div class="flex flex-col items-center space-y-4 py-4">
        <div class="text-center space-y-2">
          <h3 class="font-semibold text-lg">{{ data.title }}</h3>
          <p class="text-sm text-gray-600">Accession Number: {{ data.accession_number }}</p>
          <div class="bg-gray-100 p-4 rounded-lg">
            <p class="text-xs text-gray-500 mb-2">QR Code Data:</p>
            <p class="text-sm font-mono">Title: {{ data.title }}, Accession: {{ data.accession_number }}</p>
          </div>
        </div>
      </div>

      <DialogFooter>
        <Button @click="$emit('update:open', false)">
          Close
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'

interface BookData {
  title: string
  accession_number: string
}

interface Props {
  open: boolean
  data: BookData
}

defineProps<Props>()

defineEmits<{
  'update:open': [value: boolean]
}>()
</script>
