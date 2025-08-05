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

const invoices = [
    {
        invoice: "INV001",
        paymentStatus: "Paid",
        totalAmount: "$250.00",
        paymentMethod: "Credit Card",
    },
]

defineProps({
    transactions: Object,
})

</script>

<template>
    <Table>
        <TableCaption>A list of your recent invoices.</TableCaption>
        <TableHeader>
            <TableRow>
                <TableHead class="w-[100px]">
                    Transaction Number
                </TableHead>
                <TableHead>Patron</TableHead>
                <TableHead>Book</TableHead>
                <TableHead class="text-right">
                    Check-out Date
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="transaction in transactions.data" :key="transaction.id">
                <TableCell class="font-medium">
                    {{ transaction.transaction_number }}
                </TableCell>
                <TableCell>{{ transaction.user ? transaction.user.first_name + ' ' + transaction.user.last_name : '(borrowed inside)' }}</TableCell>
                <TableCell>{{ transaction.record.accession_number + ' --- ' + transaction.record.title }}</TableCell>
                <TableCell class="text-right">
                    {{ transaction.checkout_date }}
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>

    <PaginationLinks :paginator="transactions" />
</template>
