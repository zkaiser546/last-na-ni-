<template>
    <div>
        <!-- Tab buttons -->
        <div class="flex border-b border-gray-200">
            <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                    'px-4 py-2 -mb-px border-b-2 text-sm font-medium',
                    activeTab === tab.id
                        ? 'border-blue-500 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-300 dark:hover:text-gray-200 dark:hover:border-gray-600'                ]"
            >
                {{ tab.title }}
            </button>
        </div>

        <!-- Tab content -->
        <div class="mt-4">
            <div v-for="tab in tabs" :key="tab.id" v-show="activeTab === tab.id">
                <slot :name="tab.id">
                    {{ tab.content }}
                </slot>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';

interface Tab {
    id: string;
    title: string;
    content?: string;
}

interface Props {
    tabs: Tab[];
}

const props = defineProps<Props>();

// Simple active tab state
const activeTab = ref(props.tabs[0]?.id || '');
</script>

<style scoped>
div[v-show] {
    transition: opacity 0.2s ease-in-out;
}
</style>
