<script lang="ts">
import QrScanner from 'qr-scanner';
import { Dialog, DialogFooter, DialogHeader } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

export default {
    name: 'QrScannerComponent',
    components: { DialogFooter, DialogHeader, Button, Dialog },
    data() {
        return {
            qrScanner: null,
            isScanning: false,
            scanResult: '',
            error: '',
            cameras: [],
            currentCameraIndex: 0,
        };
    },
    mounted() {
        this.initializeScanner();
        this.getCameras();
    },
    beforeUnmount() {
        if (this.qrScanner) {
            this.qrScanner.destroy();
        }
    },
    methods: {
        async initializeScanner() {
            try {
                // Initialize QR Scanner
                this.qrScanner = new QrScanner(this.$refs.videoElement, (result) => this.onScanSuccess(result), {
                    onDecodeError: (error) => this.onScanError(error),
                    highlightScanRegion: true,
                    highlightCodeOutline: true,
                });
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
                await this.qrScanner.start();
                this.isScanning = true;
                this.error = '';
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
            this.scanResult = result.data;
            this.stopScanning();

            // Send result to Laravel backend
            this.sendToBackend(result.data);
        },

        onScanError(error) {
            // Handle scan errors (usually when no QR code is detected)
            // Don't show these as errors to the user as they're expected
        },

        clearResult() {
            this.scanResult = '';
        },

        async sendToBackend(data) {
            try {
                const response = await fetch('/api/qr-scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ qr_data: data }),
                });

                const result = await response.json();
                console.log('Backend response:', result);
            } catch (error) {
                console.error('Failed to send to backend:', error);
            }
        },
    },
};
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="outline"> Edit Profile </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Edit profile</DialogTitle>
                <DialogDescription> Make changes to your profile here. Click save when you're done. </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="qr-scanner-container">
                    <h2>QR Code Scanner</h2>

                    <!-- Video element for camera feed -->
                    <div class="scanner-wrapper">
                        <video ref="videoElement" class="scanner-video"></video>
                        <div v-if="!isScanning" class="scanner-overlay">
                            <button @click="startScanning" class="start-btn">Start Scanning</button>
                        </div>
                    </div>

                    <!-- Controls -->
                    <div class="controls">
                        <button v-if="isScanning" @click="stopScanning" class="stop-btn">Stop Scanning</button>
                        <button @click="toggleCamera" class="toggle-btn">Switch Camera</button>
                    </div>

                    <!-- Results -->
                    <div v-if="scanResult" class="result">
                        <h3>Scan Result:</h3>
                        <p>{{ scanResult }}</p>
                        <button @click="clearResult">Clear</button>
                    </div>

                    <!-- Error handling -->
                    <div v-if="error" class="error">
                        <p>Error: {{ error }}</p>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <Button type="submit"> Save changes </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<style scoped>
.qr-scanner-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.scanner-wrapper {
    position: relative;
    width: 100%;
    max-width: 400px;
    margin: 20px auto;
}

.scanner-video {
    width: 100%;
    height: 300px;
    border: 2px solid #ddd;
    border-radius: 8px;
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
    text-align: center;
    margin: 20px 0;
}

.start-btn,
.stop-btn,
.toggle-btn {
    padding: 10px 20px;
    margin: 0 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.start-btn {
    background: #28a745;
    color: white;
}

.stop-btn {
    background: #dc3545;
    color: white;
}

.toggle-btn {
    background: #007bff;
    color: white;
}

.result {
    margin: 20px 0;
    padding: 15px;
    background: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
}

.error {
    margin: 20px 0;
    padding: 15px;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    color: #721c24;
}
</style>
