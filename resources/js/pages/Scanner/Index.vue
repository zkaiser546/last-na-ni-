<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

// Form for scanner settings
const form = useForm({
    resolution: '300',
    colorMode: 'color',
    documentType: 'book',
    outputFormat: 'pdf',
});

// Scanner status and preview
const scannerStatus = ref('disconnected');
const previewImage = ref('');
const scanning = ref(false);
const scanComplete = ref(false);
const scanResults = ref(null);
const activeView = ref('scan'); // Changed from activeTab to activeView

// Mock function to connect to scanner
const connectToScanner = () => {
    scannerStatus.value = 'connecting';

    // Simulate connection delay
    setTimeout(() => {
        scannerStatus.value = 'connected';
        // In a real implementation, you would use a library to connect to the ScanSnap SV600
    }, 1500);
};

// Mock function to disconnect from scanner
const disconnectFromScanner = () => {
    if (scannerStatus.value === 'connected') {
        scannerStatus.value = 'disconnecting';

        // Simulate disconnection delay
        setTimeout(() => {
            scannerStatus.value = 'disconnected';
            previewImage.value = '';
        }, 1000);
    }
};

// Mock function to start scanning
const startScan = () => {
    if (scannerStatus.value !== 'connected') {
        return;
    }

    scanning.value = true;
    scanComplete.value = false;

    // Simulate scanning process
    setTimeout(() => {
        scanning.value = false;
        scanComplete.value = true;
        // Mock preview image - in a real implementation, this would be an image from the scanner
        previewImage.value = 'https://placehold.co/600x400/e6e6e6/cc3333?text=Scanned+Document';

        // Mock scan results
        scanResults.value = {
            timestamp: new Date().toLocaleString(),
            pages: 1,
            size: '2.3 MB',
            resolution: form.resolution + ' DPI',
            format: form.outputFormat.toUpperCase()
        };
    }, 3000);
};

// Process and save the scan
const processScan = () => {
    form.post(route('scanner.process'), {
        onSuccess: () => {
            activeView.value = 'results';
        }
    });
};

// Clean up when component is destroyed
onBeforeUnmount(() => {
    if (scannerStatus.value === 'connected') {
        disconnectFromScanner();
    }
});
</script>

<template>
    <Head title="Scanner" />
    <AppLayout>
        <div class="mx-auto p-6 max-w-7xl">
            <h1 class="text-3xl font-bold text-usepmaroon mb-8">Fujitsu ScanSnap SV600 Scanner</h1>

            <!-- Navigation buttons -->
            <div class="flex space-x-2 mb-6 border-b pb-4">
                <Button
                    variant="ghost"
                    :class="{ 'bg-muted': activeView === 'scan' }"
                    @click="activeView = 'scan'"
                >
                    Scan
                </Button>
                <Button
                    variant="ghost"
                    :class="{ 'bg-muted': activeView === 'settings' }"
                    @click="activeView = 'settings'"
                >
                    Settings
                </Button>
                <Button
                    variant="ghost"
                    :class="{ 'bg-muted': activeView === 'results' }"
                    @click="activeView = 'results'"
                    :disabled="!scanComplete"
                >
                    Results
                </Button>
            </div>

            <!-- Scan View -->
            <div v-if="activeView === 'scan'" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Scanner Control</CardTitle>
                        <CardDescription>Connect to your ScanSnap SV600 and start scanning documents</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div :class="{
                                    'w-4 h-4 rounded-full': true,
                                    'bg-red-500': scannerStatus === 'disconnected',
                                    'bg-yellow-500 animate-pulse': scannerStatus === 'connecting' || scannerStatus === 'disconnecting',
                                    'bg-green-500': scannerStatus === 'connected'
                                }"></div>
                                <span class="font-medium">Status: {{
                                    scannerStatus === 'disconnected' ? 'Disconnected' :
                                    scannerStatus === 'connecting' ? 'Connecting...' :
                                    scannerStatus === 'disconnecting' ? 'Disconnecting...' :
                                    'Connected'
                                }}</span>
                            </div>
                            <div>
                                <Button
                                    v-if="scannerStatus === 'disconnected'"
                                    @click="connectToScanner"
                                    class="bg-usepmaroon hover:bg-usepmaroon/90"
                                >
                                    Connect to Scanner
                                </Button>
                                <Button
                                    v-else-if="scannerStatus === 'connected'"
                                    @click="disconnectFromScanner"
                                    variant="destructive"
                                >
                                    Disconnect
                                </Button>
                                <Button
                                    v-else
                                    disabled
                                >
                                    Please wait...
                                </Button>
                            </div>
                        </div>

                        <div v-if="scannerStatus === 'connected'" class="space-y-6">
                            <div class="rounded-lg border border-dashed border-gray-300 p-8 text-center">
                                <div v-if="!previewImage && !scanning" class="flex flex-col items-center justify-center">
                                    <img src="https://placehold.co/120x120/e6e6e6/666666?text=SV600" alt="Scanner illustration" class="mb-4" />
                                    <p class="text-gray-500 mb-4">Place your document under the scanner and click "Start Scan"</p>
                                    <Button
                                        @click="startScan"
                                        class="bg-usepmaroon hover:bg-usepmaroon/90"
                                    >
                                        Start Scan
                                    </Button>
                                </div>

                                <div v-else-if="scanning" class="flex flex-col items-center justify-center h-64">
                                    <div class="animate-pulse flex flex-col items-center">
                                        <div class="w-12 h-1 bg-usepmaroon mb-4 animate-scanning"></div>
                                        <p class="text-usepmaroon font-medium">Scanning in progress...</p>
                                    </div>
                                </div>

                                <div v-else-if="previewImage" class="flex flex-col items-center justify-center">
                                    <img :src="previewImage" alt="Scan preview" class="max-h-96 mb-4 shadow-md rounded" />
                                    <div class="flex gap-3 mt-4">
                                        <Button @click="startScan" variant="outline">Scan Again</Button>
                                        <Button @click="processScan" class="bg-usepmaroon hover:bg-usepmaroon/90">Save Scan</Button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="scanComplete" class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-lg mb-2">Scan Information</h3>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="text-gray-500">Time:</span>
                                        <span class="ml-2">{{ scanResults.timestamp }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Pages:</span>
                                        <span class="ml-2">{{ scanResults.pages }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Size:</span>
                                        <span class="ml-2">{{ scanResults.size }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Resolution:</span>
                                        <span class="ml-2">{{ scanResults.resolution }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Format:</span>
                                        <span class="ml-2">{{ scanResults.format }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Settings View -->
            <div v-if="activeView === 'settings'" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Scanner Settings</CardTitle>
                        <CardDescription>Configure your ScanSnap SV600 settings</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="processScan" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <Label for="resolution">Resolution (DPI)</Label>
                                    <Select v-model="form.resolution">
                                        <SelectTrigger id="resolution">
                                            <SelectValue placeholder="Select resolution" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="150">150 DPI</SelectItem>
                                            <SelectItem value="300">300 DPI</SelectItem>
                                            <SelectItem value="600">600 DPI</SelectItem>
                                            <SelectItem value="1200">1200 DPI</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div class="space-y-2">
                                    <Label for="colorMode">Color Mode</Label>
                                    <Select v-model="form.colorMode">
                                        <SelectTrigger id="colorMode">
                                            <SelectValue placeholder="Select color mode" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="color">Color</SelectItem>
                                            <SelectItem value="grayscale">Grayscale</SelectItem>
                                            <SelectItem value="bw">Black & White</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div class="space-y-2">
                                    <Label for="documentType">Document Type</Label>
                                    <Select v-model="form.documentType">
                                        <SelectTrigger id="documentType">
                                            <SelectValue placeholder="Select document type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="book">Book</SelectItem>
                                            <SelectItem value="document">Document</SelectItem>
                                            <SelectItem value="photo">Photo</SelectItem>
                                            <SelectItem value="card">ID Card/Business Card</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div class="space-y-2">
                                    <Label for="outputFormat">Output Format</Label>
                                    <Select v-model="form.outputFormat">
                                        <SelectTrigger id="outputFormat">
                                            <SelectValue placeholder="Select output format" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="pdf">PDF</SelectItem>
                                            <SelectItem value="jpg">JPEG</SelectItem>
                                            <SelectItem value="png">PNG</SelectItem>
                                            <SelectItem value="tiff">TIFF</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                        </form>
                    </CardContent>
                    <CardFooter>
                        <Button @click="activeView = 'scan'" class="bg-usepmaroon hover:bg-usepmaroon/90">Apply Settings</Button>
                    </CardFooter>
                </Card>
            </div>

            <!-- Results View -->
            <div v-if="activeView === 'results'" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Scan Results</CardTitle>
                        <CardDescription>View and manage your scanned documents</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div v-if="scanComplete" class="space-y-6">
                            <div class="flex items-center justify-center p-6">
                                <img :src="previewImage" alt="Scan result" class="max-h-96 shadow-lg rounded" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                                <div>
                                    <span class="text-gray-500">File Name:</span>
                                    <span class="ml-2">Scan_{{ scanResults.timestamp.replace(/[/: ]/g, '_') }}.{{ form.outputFormat }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">File Size:</span>
                                    <span class="ml-2">{{ scanResults.size }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Resolution:</span>
                                    <span class="ml-2">{{ scanResults.resolution }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Format:</span>
                                    <span class="ml-2">{{ scanResults.format }}</span>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <div class="flex gap-3">
                                    <Button variant="outline">Download</Button>
                                    <Button variant="outline">Print</Button>
                                </div>
                                <Button class="bg-usepmaroon hover:bg-usepmaroon/90" @click="activeView = 'scan'">New Scan</Button>
                            </div>
                        </div>

                        <div v-else class="text-center py-12">
                            <p class="text-gray-500">No scan results available. Please complete a scan first.</p>
                            <Button @click="activeView = 'scan'" class="mt-4">Go to Scanner</Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.animate-scanning {
    animation: scanning 1.5s infinite;
}

@keyframes scanning {
    0% {
        transform: translateY(0) scaleX(0.5);
    }
    50% {
        transform: translateY(0) scaleX(1);
    }
    100% {
        transform: translateY(0) scaleX(0.5);
    }
}
</style>
