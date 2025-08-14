<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import axios from 'axios';

// Active view state
const activeView = ref('scan');

// Scan results data
const scans = ref([]);
const loading = ref(false);

// Scanner status for UI display
const scannerStatus = ref('ready');

// Function to switch between views
const setActiveView = (view: string) => {
    activeView.value = view;
    if (view === 'results') {
        fetchScans();
    }
};

// Fetch all scans from API
const fetchScans = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/scans');
        scans.value = response.data.data;
    } catch (error) {
        console.error('Error fetching scans:', error);
    } finally {
        loading.value = false;
    }
};

// Delete a scan
const deleteScan = async (scanId: number) => {
    if (!confirm('Are you sure you want to delete this scan?')) {
        return;
    }

    try {
        await axios.delete(`/api/scans/${scanId}`);
        // Remove from local array
        scans.value = scans.value.filter(scan => scan.id !== scanId);
    } catch (error) {
        console.error('Error deleting scan:', error);
        alert('Failed to delete scan');
    }
};

// Format file size for display
const formatFileSize = (bytes: number): string => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Format date for display
const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleString();
};

// Manual import trigger
const triggerImport = async () => {
    loading.value = true;
    try {
        await axios.post('/api/scan/import');
        if (activeView.value === 'results') {
            fetchScans();
        }
    } catch (error) {
        console.error('Error triggering import:', error);
    } finally {
        loading.value = false;
    }
};

// Load scans on component mount if we're on results view
onMounted(() => {
    if (activeView.value === 'results') {
        fetchScans();
    }
});
</script>

<template>
    <Head title="Scanner" />
    <AppLayout>
        <div class="mx-auto p-6 max-w-7xl">
            <h1 class="text-3xl font-bold text-usepmaroon mb-8">Fujitsu ScanSnap SV600 Scanner</h1>

            <!-- Navigation buttons -->
            <div class="flex space-x-4 mb-8">
                <Button
                    @click="setActiveView('scan')"
                    :variant="activeView === 'scan' ? 'default' : 'outline'"
                    class="px-6 py-2"
                >
                    Scanner Control
                </Button>
                <Button
                    @click="setActiveView('results')"
                    :variant="activeView === 'results' ? 'default' : 'outline'"
                    class="px-6 py-2"
                >
                    Scan Results
                </Button>
            </div>

            <!-- Scanner Control View -->
            <div v-if="activeView === 'scan'" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>ScanSnap Home Integration</CardTitle>
                        <CardDescription>
                            This system monitors the ScanSnap Home output folder and automatically imports new scans.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-900 mb-2">How to use:</h3>
                            <ol class="list-decimal list-inside space-y-1 text-blue-800">
                                <li>Open ScanSnap Home software</li>
                                <li>Configure it to save scans to your designated output folder</li>
                                <li>Scan documents using your SV600 scanner</li>
                                <li>Files will automatically appear in the "Scan Results" tab within 1 minute</li>
                            </ol>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium">Scanner Status:</p>
                                <p class="text-sm text-gray-600">Ready for ScanSnap Home integration</p>
                            </div>
                            <Button @click="triggerImport" :disabled="loading">
                                {{ loading ? 'Checking...' : 'Check for New Scans' }}
                            </Button>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="font-semibold text-yellow-900 mb-2">Configuration Required:</h4>
                            <p class="text-yellow-800 text-sm">
                                Make sure to set the <code>SCANSNAP_OUTPUT_PATH</code> in your .env file to match
                                your ScanSnap Home output folder location.
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Results View -->
            <div v-if="activeView === 'results'" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Scan Results</CardTitle>
                        <CardDescription>
                            View and manage your imported scanned documents
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <!-- Loading spinner -->
                        <div v-if="loading" class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-usepmaroon"></div>
                            <span class="ml-2">Loading scans...</span>
                        </div>

                        <!-- No scans message -->
                        <div v-else-if="scans.length === 0" class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No scans found</h3>
                            <p class="text-gray-500">
                                Start scanning with ScanSnap Home to see your documents here
                            </p>
                        </div>

                        <!-- Scans table -->
                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            File Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Size
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date Scanned
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="scan in scans" :key="scan.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <svg v-if="scan.mime_type.includes('pdf')" class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <svg v-else class="h-8 w-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ scan.original_filename }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ scan.mime_type }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ formatFileSize(scan.file_size) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ formatDate(scan.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a
                                                :href="scan.public_url"
                                                target="_blank"
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                Download
                                            </a>
                                            <Button
                                                @click="deleteScan(scan.id)"
                                                variant="destructive"
                                                size="sm"
                                            >
                                                Delete
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
