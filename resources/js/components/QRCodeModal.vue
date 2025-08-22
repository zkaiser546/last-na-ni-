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
import { computed, ref, nextTick } from 'vue'
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

const downloadQRCode = async () => {
  if (!qrCodeRef.value) return

  try {
    // Wait for the next tick to ensure the component is fully rendered
    await nextTick()

    // Get the modal content element to search within
    const modalContent = document.querySelector('[role="dialog"]')
    if (!modalContent) {
      console.error('Modal content not found')
      alert('Unable to access QR code for download. Please try again.')
      return
    }

    // Find canvas element within the modal
    const canvas = modalContent.querySelector('canvas')

    if (!canvas) {
      console.error('Canvas element not found in modal')
      alert('Unable to access QR code for download. Please try again.')
      return
    }

    // Convert canvas to data URL
    const dataURL = canvas.toDataURL('image/png')

    if (!dataURL || dataURL === 'data:,') {
      console.error('Failed to generate image data')
      alert('Failed to generate QR code image. Please try again.')
      return
    }

    // Create download link
    const link = document.createElement('a')
    const sanitizedTitle = props.data.title.replace(/[^a-zA-Z0-9]/g, '_')
    link.download = `QR_${props.data.accession_number}_${sanitizedTitle}.png`
    link.href = dataURL

    // Trigger download
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)

    console.log('QR code download successful')
  } catch (error) {
    console.error('Error downloading QR code:', error)
    alert('An error occurred while downloading the QR code. Please try again.')
  }
}
</script>
