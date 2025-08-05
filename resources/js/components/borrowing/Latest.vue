<script setup lang="ts">

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import PaginationLinks from '@/components/PaginationLinks.vue';

defineProps({
    transactions: Object,
})

</script>

<template>
    <template>
        hi sa sulod huhu
        <!--
        <Table>
            <TableHeader>
                <TableRow >
                    <TableHead >First Name</TableHead>
                    <TableHead >Last Name</TableHead>
                    <TableHead >Email</TableHead>
                    <TableHead >User Type</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>

                <TableRow v-for="transaction in transactions.data" :key="transaction.id">
                {{ console.log(transaction.transaction_number)}}
                    <TableCell >
                        hi
                        {{ transaction.transaction_number }}
                    </TableCell>
                    <TableCell >
                        {{ transaction.user_id }}
                    </TableCell>
                    <TableCell >
                        {{ transaction.record_id }}
                    </TableCell>
                    <TableCell >
                        {{ transaction.checkout_date }}
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>


        <PaginationLinks :paginator="transactions" />
        -->

    </template>
</template>

<style scoped>

</style>
