import { h } from 'vue'
import { ColumnDef } from '@tanstack/vue-table';

export interface Payment {
    id: string
    amount: number
    status: 'pending' | 'processing' | 'success' | 'failed'
    email: string
}

export const payments: Payment[] = [
    {
        id: '728ed52f',
        amount: 100,
        status: 'pending',
        email: 'm@example.com',
    },
    {
        id: '489e1d42',
        amount: 125,
        status: 'processing',
        email: 'example@gmail.com',
    },
    // ...
]

export const columns: ColumnDef<Payment>[] = [
    {
        accessorKey: 'id',
        header: () => h('div', { class: 'text-right' }, 'ID'),
        cell: ({ row }) => {
            return h('div', { class: 'text-right font-medium' }, row.getValue('id'))
        },
    },
    {
        accessorKey: 'amount',
        header: () => h('div', { class: 'text-right' }, 'Amount'),
        cell: ({ row }) => {
            const amount = Number.parseFloat(row.getValue('amount'))
            const formatted = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            }).format(amount)

            return h('div', { class: 'text-right font-medium' }, formatted)
        },
    },
    {
        accessorKey: 'status',
        header: () => h('div', { class: 'text-right' }, 'Status'),
        cell: ({ row }) => {
            return h('div', { class: 'text-right font-medium' }, row.getValue('status'))
        },
    },
    {
        accessorKey: 'email',
        header: () => h('div', { class: 'text-right' }, 'Email'),
        cell: ({ row }) => {
            return h('div', { class: 'text-right font-medium' }, row.getValue('email'))
        },
    },

]
