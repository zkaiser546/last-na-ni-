<script lang="ts">
import QrScanner from 'qr-scanner';
import { Dialog, DialogContent, DialogTrigger, DialogFooter, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

export default {
    name: 'QrScannerComponent',
    components: {
        Dialog,
        DialogContent,
        DialogTrigger,
        DialogFooter,
        DialogHeader,
        DialogTitle,
        DialogDescription,
        Button
    },
    data() {
        return {
            qrScanner: null,
            isScanning: false,
            scanResult: '',
            error: '',
            cameras: [],
            currentCameraIndex: 0,
            isDialogOpen: false,
        };
    },
    watch: {
        isDialogOpen(newVal) {
            if (newVal) {
                this.$nextTick(() => {
                    this.initializeScanner();
                    this.getCameras();
                });
            } else {
                this.cleanup();
            }
        }
    },
    beforeUnmount() {
        this.cleanup();
    },
    methods: {
        cleanup() {
            if (this.qrScanner) {
                this.qrScanner.destroy();
                this.qrScanner = null;
            }
            this.isScanning = false;
        },

        async initializeScanner() {
            try {
                if (this.$refs.videoElement) {
                    this.qrScanner = new QrScanner(this.$refs.videoElement, (result) => this.onScanSuccess(result), {
                        onDecodeError: (error) => this.onScanError(error),
                        highlightScanRegion: true,
                        highlightCodeOutline: true,
                    });
                }
            } catch (error) {
                this.error = 'Failed to initialize scanner: ' + error.message;
            }
        },

        async getCameras() {
            try {
                this.cameras = await QrScanner.listCameras(true);
            } catch (error) {
                this.error = 'Failed to get cameras: ' + error.message;
            }
        },

        async startScanning() {
            try {
                if (this.qrScanner) {
                    await this.qrScanner.start();
                    this.isScanning = true;
                    this.error = '';
                }
            } catch (error) {
                this.error = 'Failed to start scanning: ' + error.message;
            }
        },

        stopScanning() {
            if (this.qrScanner) {
                this.qrScanner.stop();
                this.isScanning = false;
            }
        },

        async toggleCamera() {
            if (this.cameras.length > 1) {
                this.currentCameraIndex = (this.currentCameraIndex + 1) % this.cameras.length;
                const camera = this.cameras[this.currentCameraIndex];
                await this.qrScanner.setCamera(camera.id);
            }
        },

        onScanSuccess(result) {
            if (this.isValidQRCode(result)) {
                this.scanResult = result.data;
                this.stopScanning();
                this.sendToBackend(result.data);
            } else {
                // Handle invalid result
                this.error = "Invalid book!, please scan again";
                this.stopScanning();
                // Optionally continue scanning

            }
        },

        isValidQRCode(result) {
            // Check if result.data is an integer up to 5 digits
            const num = parseInt(result.data);
            return Number.isInteger(num) && num >= 0 && num <= 99999;
        },

        onScanError(error) {
            // handle error here silently
        },

        clearResult() {
            this.scanResult = '';
        },

        async sendToBackend(data) {
            console.log('nice kaayo ' + data)
        },

        openDialog() {
            this.isDialogOpen = true;
        },

        closeDialog() {
            this.isDialogOpen = false;
            this.clearResult();
            this.error = '';
        }
    },
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <Button variant="outline" @click="openDialog">Open QR Scanner</Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>QR Code Scanner</DialogTitle>
                <DialogDescription>
                    Position a QR code in front of your camera to scan it.
                </DialogDescription>
            </DialogHeader>

            <div class="qr-scanner-container">
                <!-- Video element for camera feed -->
                <div class="scanner-wrapper">
                    <video ref="videoElement" class="scanner-video"></video>
                    <div v-if="!isScanning" class="scanner-overlay">
                        <Button @click="startScanning" class="start-btn">Start Scanning</Button>
                    </div>
                </div>

                <!-- Controls -->
                <div class="controls">
                    <Button v-if="isScanning" @click="stopScanning" variant="destructive" size="sm">
                        Stop Scanning
                    </Button>
                    <Button @click="toggleCamera" variant="outline" size="sm">
                        Switch Camera
                    </Button>
                </div>

                <!-- Results -->
                <div v-if="scanResult" class="result">
                    <h4 class="font-semibold">Scan Result:</h4>
                    <p class="text-sm break-all">{{ scanResult }}</p>
                    <Button @click="clearResult" variant="outline" size="sm" class="mt-2">
                        Clear
                    </Button>
                </div>

                <!-- Error handling -->
                <div v-if="error" class="error">
                    <p class="text-sm">Error: {{ error }}</p>
                </div>
            </div>

            <DialogFooter>
                <Button @click="closeDialog" variant="outline">Close</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<style scoped>
.qr-scanner-container {
    padding: 10px 0;
}

.scanner-wrapper {
    position: relative;
    width: 100%;
    margin: 15px 0;
}

.scanner-video {
    width: 100%;
    height: 250px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    background: #000;
}

.scanner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 8px;
}

.controls {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin: 15px 0;
}

.result {
    margin: 15px 0;
    padding: 12px;
    background: #f0f9ff;
    border: 1px solid #e0f2fe;
    border-radius: 6px;
}

.error {
    margin: 15px 0;
    padding: 12px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 6px;
    color: #dc2626;
}
</style>
