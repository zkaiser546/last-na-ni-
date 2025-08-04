<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
interface PaginatorLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Paginator {
    links: PaginatorLink[];
    from: number;
    to: number;
    total: number;
}

defineProps<{
    paginator: Paginator;
}>();

</script>

<template>
    <div class="flex justify-between items-start">
        <div class="flex gap-4 items-center rounded-md overflow-hidden shadow-lg">
            <div v-for="(link, i) in paginator.links" :key="i">
                <template v-if="link.url === null && link.label.includes('Previous')">
                    <span v-html="link.label" />
                </template>
                <template v-if="link.url !== null">
                    <Link :href="link.url">{{ link.label }}</Link>
                </template>
            </div>
        </div>

        <p class="text-slate-600 dark:text-slate-400 text-sm">
            Showing {{ paginator.from }} to {{ paginator.to }} of
            {{ paginator.total }} results
        </p>
    </div>
</template>
