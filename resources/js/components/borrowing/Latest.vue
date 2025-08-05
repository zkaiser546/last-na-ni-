<script setup lang="ts">

defineProps({
    transactions: Object,
})
</script>

<template>
    <div v-if="Object.keys(transactions?.data).length">
        <div class="grid grid-cols-3 gap-4">
            <div v-for="transaction in transactions?.data" :key="transaction.id">
                {{ transaction.transaction_number }}
            </div>
        </div>
    </div>
    <div v-else>
        no records found
    </div>
</template>

<style scoped>

</style>
