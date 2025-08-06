<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"
import PaginationLinks from '@/components/PaginationLinks.vue';

defineProps({
    transactions: Object,
})

</script>

<template>
    <Table>
        <TableCaption>A list of your recent borrowing transactions.</TableCaption>
        <TableHeader>
            <TableRow>
                <TableHead class="w-[100px]">
                    User
                </TableHead>
                <TableHead>Entry Time</TableHead>
                <TableHead>Exit Time</TableHead>
                <TableHead class="text-right">
                    Purpose
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="transaction in transactions.data" :key="transaction.id">
                <TableCell class="font-medium">
                    {{ transaction.user_id }}
                </TableCell>
                <TableCell>{{ transaction.entry_time }}</TableCell>
                <TableCell>{{ transaction.exit_time }}</TableCell>
                <TableCell class="text-right">
                    {{ transaction.visit_purpose_id }}
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>

    <PaginationLinks :paginator="transactions" />
</template>
