<script setup lang="ts">
import { ref, reactive, onMounted, nextTick, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';

const showAlert = ref(false);
const alertMessage = ref('');
const alertType = ref<'success' | 'error'>('success');
const showStudentInfo = ref(false);
const studentIdInput = ref('');
const currentStudent = reactive({
    initials: '',
    name: '',
    id: '',
    program: ''
});
const studentIdInputRef = ref<HTMLInputElement | null>(null);
const scannerInputRef = ref<HTMLInputElement | null>(null);
let resetTimer: ReturnType<typeof setTimeout> | null = null;
let scannerBuffer = '';
let scannerTimeout: ReturnType<typeof setTimeout>;

const props = defineProps({
    patron: Object,
    search_button: Boolean,
    is_logout: Boolean,
});

const form = useForm({
    search: '',
    patron_id: null,
});

function showAlertMsg(msg: string, type: 'success' | 'error' = 'success') {
    alertMessage.value = msg;
    alertType.value = type;
    showAlert.value = true;
    setTimeout(() => showAlert.value = false, 2500);
}

function resetDisplay() {
    showStudentInfo.value = false;
    form.search = '';
    nextTick(() => studentIdInputRef.value?.focus());
}

function submitManualId() {
    if (!form.search.trim()) {
        showAlertMsg('Please enter a valid Student ID.', 'error');
        return;
    }
    processStudentId(form.search.trim());
}

function onScannerInput(e: Event) {
    const val = (e.target as HTMLInputElement).value;
    if (val) {
        processStudentId(val);
        (e.target as HTMLInputElement).value = '';
    }
}

function submitForm() {
    if (!props.patron) return;

    form.post(route('logger.store'), {
        onSuccess: (response) => {
            if (response?.props?.flash?.success) {
                showAlertMsg(response.props.flash.success);
            }
            form.reset();
            resetDisplay();
            nextTick(() => {
                if (studentIdInputRef.value) {
                    studentIdInputRef.value.focus();
                }
            });
        },
        onError: (errors) => {
            showAlertMsg('An error occurred.', 'error');
            console.error('Form submission errors:', errors);
        }
    });
}

function processStudentId(id: string) {
    // Clear the input immediately to avoid duplicate scans
    form.search = '';
    scannerBuffer = '';

    router.get(route('logger.create'), { search: id, search_button: true }, {
        preserveState: true,
        onSuccess: () => {
            if (props.patron) {
                showStudentInfo.value = true;
                submitForm(); // Automatically submit after finding user
            }
        },
        onError: () => {
            showAlertMsg('Student not found.', 'error');
            resetDisplay();
        }
    });
}

// Watch for flash messages from the server
watch(() => usePage().props.flash, (flash) => {
    if (flash.success) {
        showAlertMsg(flash.success);
    } else if (flash.error) {
        showAlertMsg(flash.error, 'error');
    }
}, { immediate: true });

// Watch for patron changes
watch(() => props.patron, (newPatron) => {
    if (newPatron) {
        currentStudent.name = `${newPatron.first_name} ${newPatron.last_name}`;
        currentStudent.id = newPatron.library_id;
        currentStudent.program = newPatron.program || 'N/A';
        currentStudent.initials = newPatron.first_name.charAt(0) + newPatron.last_name.charAt(0);
        form.patron_id = newPatron.id;
        showStudentInfo.value = true;
    }
}, { immediate: true });

onMounted(() => {
    nextTick(() => studentIdInputRef.value?.focus());
    // Barcode scanner support (global keydown)
    window.addEventListener('keydown', (e) => {
        if (document.activeElement !== studentIdInputRef.value && e.key.length === 1) {
            scannerBuffer += e.key;
            clearTimeout(scannerTimeout);
            scannerTimeout = setTimeout(() => {
                if (scannerBuffer.length > 0) {
                    processStudentId(scannerBuffer);
                    scannerBuffer = '';
                }
            }, 100);
        }
    });
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 font-sans relative flex items-center justify-center bg-cover bg-center" style="background-image: url('/images/eagle.jpg')">
        <!-- Alert Message -->
        <div v-if="showAlert" :class="['fixed top-6 left-1/2 transform -translate-x-1/2 px-8 py-4 rounded-lg shadow-lg z-50 font-bold text-lg min-w-[320px] text-center text-white transition-all duration-300', alertType === 'success' ? 'bg-green-600' : 'bg-red-600']">
            {{ alertMessage }}
        </div>

        <div class="w-full max-w-md mx-4 bg-white rounded-xl shadow-lg p-8 transition-all">
            <div v-if="showStudentInfo && patron" class="text-center transition-all fade-in">
                <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center mx-auto mb-5 text-5xl font-bold text-gray-700 shadow-md">
                    {{ currentStudent.initials }}
                </div>
                <h3 class="text-xl font-bold">{{ currentStudent.name }}</h3>
                <p class="text-gray-600">ID: {{ currentStudent.id }}</p>
                <p class="text-gray-600">Program: {{ currentStudent.program }}</p>
            </div>
            <div v-else>
                <div class="text-center transition-all fade-in">
                    <img src="/storage/images/usepLogo.svg" alt="USEP Logo" class="h-24 mx-auto mb-3">
                    <h1 class="text-2xl font-bold text-usepmaroon mb-1">USEP CAMPUS LIBRARY</h1>
                    <p class="text-lg text-gray-600">Tagum-Mabini Campus</p>
                </div>
                <div class="mt-5 transition-all">
                    <p class="text-lg font-medium text-center mb-3">Please enter your Student ID</p>
                    <input
                        v-model="form.search"
                        @keyup.enter="submitManualId"
                        class="w-full p-4 border-2 border-usepmaroon rounded-lg text-lg my-5"
                        placeholder="e.g. 2023-12345 or 202312345"
                        autofocus
                        ref="studentIdInputRef"
                    >
                    <p class="text-sm text-gray-500 mt-2 text-center">Or scan your RFID/Library card Barcode</p>
                </div>
            </div>
        </div>
        <!-- Hidden input for scanner (for barcode support) -->
        <input ref="scannerInputRef" class="absolute opacity-0 h-0 w-0" @input="onScannerInput" />
    </div>
</template>

<style scoped>
.fade-in {
    animation: fadeIn 0.5s ease-in;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.text-usepmaroon {
    color: #800000;
}
.border-usepmaroon {
    border-color: #800000;
}
</style>
