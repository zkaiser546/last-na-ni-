<script setup lang="ts">

import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { UserRoundSearch } from 'lucide-vue-next';
import { router, useForm } from '@inertiajs/vue3';
import LoggerDialog from '@/components/LoggerDialog.vue';

const props = defineProps({
    patron: Object,
    purposes: Object,
    search_button: Boolean,
    is_logout: Boolean,
});

const form = useForm({
    search: '',
});

const search = () => {
    router.get(route('logger.create'), { search: form.search, search_button: true }, { preserveState: true });
};

const clearSearch = () => {
    form.search = ''; // Clear the search input
    // router.get(route('logger.create'), { search: '' }, { preserveState: true }); // Update the route
};

</script>

<template>
    <div class="relative w-full max-w-sm items-center dark:text-card-foreground">
        <form @submit.prevent="search">
            <Input required v-model="form.search" id="search" type="number"
                   placeholder="Enter Library ID" class="pl-10" autocomplete="off"/>
        </form>
        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
          <UserRoundSearch class="size-6 text-muted-foreground" />
        </span>
    </div>
    <div class="flex gap-2 dark:text-card-foreground">
        <Button variant="outline" v-if="form.search" @click="clearSearch">Clear</Button>
        <Button @click="search">Search</Button>
    </div>

    <div v-if="patron" class="max-w-md">

        <LoggerDialog :is_logout="is_logout" :purposes="purposes" :patron="patron" :modal="true"/>

    </div>
    <div v-else-if="!patron && search_button" class="dark:text-card-foreground">
        No records found
    </div>
</template>

<style scoped>

</style>
