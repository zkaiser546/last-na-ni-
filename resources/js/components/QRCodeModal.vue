<template>
  <Dialog :open="open" @update:open="$emit('update:open', $event)">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>Book QR Code</DialogTitle>
        <DialogDescription>
          QR code for the newly created book record
        </DialogDescription>
      </DialogHeader>

      <div class="flex flex-col items-center space-y-4 py-4">
        <div class="bg-white p-4 rounded-lg border">
          <QRCodeVue3
            ref="qrCodeRef"
            :value="qrValue"
            :width="200"
            :height="200"
            :corners-square-color="'#000000'"
            :corners-dot-color="'#000000'"
            :color="'#000000'"
            :bg-color="'#ffffff'"
          />
        </div>

        <div class="text-center space-y-2">
          <h3 class="font-semibold text-lg">{{ data.title }}</h3>
          <p class="text-sm text-gray-600">Accession Number: {{ data.accession_number }}</p>
          <div class="bg-gray-100 p-4 rounded-lg">
            <p class="text-xs text-gray-500 mb-2">QR Code Data:</p>
            <p class="text-sm font-mono">{{ qrValue }}</p>
          </div>
        </div>
      </div>

      <DialogFooter class="flex justify-between">
        <Button variant="outline" @click="$emit('update:open', false)">
          Close
        </Button>
        <Button @click="downloadQRCode">
          <Download class="w-4 h-4 mr-2" />
          Download PNG
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import QRCodeVue3 from 'qrcode.vue'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { Download } from 'lucide-vue-next'

interface BookData {
  title: string
  accession_number: string
}

interface Props {
  open: boolean
  data: BookData
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'update:open': [value: boolean]
}>()

const qrCodeRef = ref()

const qrValue = computed(() => {
  return props.data.accession_number
})

const downloadQRCode = () => {
  if (!qrCodeRef.value) return

  try {
    // Get the canvas element from the QR code component
    const canvas = qrCodeRef.value.$el.querySelector('canvas')
    if (!canvas) return

    // Convert canvas to data URL
    const dataURL = canvas.toDataURL('image/png')

    // Create download link
    const link = document.createElement('a')
    link.download = `QR_${props.data.accession_number}_${props.data.title.replace(/[^a-zA-Z0-9]/g, '_')}.png`
    link.href = dataURL

    // Trigger download
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  } catch (error) {
    console.error('Error downloading QR code:', error)
  }
}
</script>
