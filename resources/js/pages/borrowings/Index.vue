<script setup lang="ts">
// import Layout from './Layout'
import { Head, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { Button } from '@/components/ui/button'
// Layout
import {
    FlexRender,
    getCoreRowModel,
    getExpandedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable, VisibilityState
} from '@tanstack/vue-table';
import { ArrowUpDown, ChevronDown, ListFilter, X } from 'lucide-vue-next';

import { h, ref } from 'vue'
import DropdownAction from './DataTableDemoColumn.vue'
import { Checkbox } from '@/components/ui/checkbox'
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import {
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { valueUpdater } from '@/lib/utils'
import { ChevronRightIcon, ChevronLeftIcon, DoubleArrowLeftIcon, DoubleArrowRightIcon } from "@radix-icons/vue";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";

import { Plus } from 'lucide-vue-next'

interface Props {
    data?: {
        data: any[]
        current_page?: number
        per_page?: number
        last_page?: number
    }
    filter?: any[]
    currentSortField?: string
    currentSortDirection?: string
    ddcClasses?: any[]
}

const props = withDefaults(defineProps<Props>(), {
    data: () => ({ data: [], current_page: 1, per_page: 10, last_page: 1 }),
    filter: () => [],
    currentSortField: undefined,
    currentSortDirection: 'asc',
    ddcClasses: () => []
})

import type { Table, Row, Column, SortingState, ColumnFiltersState, ColumnDef } from '@tanstack/vue-table'
type RowData = any
const data = props.data.data; // Now safe to access directly
const columns: ColumnDef<RowData>[] = [
    {
        id: 'search',
        // This is a virtual column for searching, not displayed
        accessorFn: (row) => `${row.accession_number} ${row.title}`,
        enableSorting: false,
        enableHiding: false,
    },
    {
        id: 'select',
        header: ({ table }: { table: Table<RowData> }) => h(Checkbox, {
            'checked': table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'),
            'onUpdate:checked': (value:boolean) => table.toggleAllPageRowsSelected(!!value),
            'ariaLabel': 'Select all',
        }),
        cell: ({ row }: { row: Row<RowData> }) => h(Checkbox, {
            'checked': row.getIsSelected(),
            'onUpdate:checked': (value: boolean) => row.toggleSelected(!!value),
            'ariaLabel': 'Select row',
        }),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'transaction_number',
        header: ({ column }: { column: Column<RowData, any> }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => {
                    // Get current sort state
                    const currentSort = column.getIsSorted();

                    // Cycle through: none -> asc -> desc -> none
                    if (currentSort === false) {
                        column.toggleSorting(false); // Set to ascending
                    } else if (currentSort === 'asc') {
                        column.toggleSorting(true);  // Set to descending
                    } else {
                        column.clearSorting();       // Clear sorting
                    }
                },
            }, () => ['T.N.', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }: { row: Row<RowData> }) => h('div', { class: 'max-w-48 whitespace-normal break-words' },
            row.getValue('transaction_number')),
        enableHiding: false,
    },
    {
        accessorKey: 'client',
        header: ({ column }: { column: Column<RowData, any> }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => {
                    const currentSort = column.getIsSorted();
                    if (currentSort === false) {
                        column.toggleSorting(false); // asc
                    } else if (currentSort === 'asc') {
                        column.toggleSorting(true);  // desc
                    } else {
                        column.clearSorting();       // none
                    }
                },
            }, () => ['Client', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }: { row: Row<RowData> }) => {
            const user = row.original.user;
            if (user) {
                return h('div', user.first_name + ' ' + user.last_name  || 'Unknown')
            } else {
                return h('div', '(borrowed inside)')
            }
        },
        enableHiding: false,
    },
    {
        accessorKey: 'book',
        header: ({ column }: { column: Column<RowData, any> }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => {
                    const currentSort = column.getIsSorted();
                    if (currentSort === false) {
                        column.toggleSorting(false); // asc
                    } else if (currentSort === 'asc') {
                        column.toggleSorting(true);  // desc
                    } else {
                        column.clearSorting();       // none
                    }
                },
            }, () => ['Book', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }: { row: Row<RowData> }) => {
            const book = row.original.record;
            if (book) {
                return h('div', { class: 'max-w-64 truncate' }, book.accession_number + '---' +book.title || 'Unknown')
            } else {
                return h('div', '(borrowed inside)')
            }
        },
        enableHiding: false,
    },
    {
        accessorKey: 'date',
        header: ({ column }: { column: Column<RowData, any> }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => {
                    // Get current sort state
                    const currentSort = column.getIsSorted();

                    // Cycle through: none -> asc -> desc -> none
                    if (currentSort === false) {
                        column.toggleSorting(false); // Set to ascending
                    } else if (currentSort === 'asc') {
                        column.toggleSorting(true);  // Set to descending
                    } else {
                        column.clearSorting();       // Clear sorting
                    }
                },
            }, () => ['Date', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }: { row: Row<RowData> }) => {
            const date = row.original.return_date ?? row.original.checkout_date;
            return h('div', { class: 'max-w-64 truncate' }, date)
        },
        enableHiding: false,
    },
    {
        accessorKey: 'transaction_type',
        header: 'Transaction Type',
        cell: ({ row }: { row: Row<RowData> }) => h('div', { class: 'max-w-48 whitespace-normal break-words' },
            row.getValue('transaction_type')),
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }: { row: Row<RowData> }) => {
            const payment = row.original

            return h('div', { class: 'relative' }, h(DropdownAction, {
                payment,
                onExpand: row.toggleExpanded,
            }))
        },
    },
]

console.log(data);

const sorting = ref<SortingState>(
    props.currentSortField ? [{
        id: props.currentSortField,
        desc: props.currentSortDirection === 'desc'
    }] : []
)
const columnFilters = ref<ColumnFiltersState>(
    props.filter ? props.filter.map(f => ({ id: f.id, value: f.value })) : []
)
const columnVisibility = ref<VisibilityState>({
    search: false, // Hide the search column by default
})
const rowSelection = ref({})
const expanded = ref({})
const pageSizes = [1, 2, 3, 5, 10, 15, 30, 40, 50, 100,];
const pagination = ref({
    pageIndex: (props.data?.current_page ?? 1) - 1,
    pageSize: props.data?.per_page ?? 10,
})

const table = useVueTable({
    data,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    pageCount: props.data?.last_page ?? 1,
    manualPagination: true,
    manualSorting: true,
    manualFiltering: true,
    // Replace your onPaginationChange handler with this:
    onPaginationChange: updater => {
        if (typeof updater === 'function') {
            pagination.value = updater(pagination.value);
        } else {
            pagination.value = updater;
        }

        // Build filters object (same logic as in onColumnFiltersChange)
        let filters: Record<string, any> = {}
        if (columnFilters.value && columnFilters.value.length > 0) {
            filters = columnFilters.value.reduce((acc: Record<string, any>, filter) => {
                if (Array.isArray(filter.value) && filter.value.length > 0) {
                    acc[filter.id] = filter.value
                } else if (!Array.isArray(filter.value) && filter.value !== '' && filter.value !== null && filter.value !== undefined) {
                    acc[filter.id] = filter.value
                }
                return acc
            }, {})
        }

        router.get(
            route('records.index'),
            {
                page: pagination.value.pageIndex + 1,
                per_page: pagination.value.pageSize,
                sort_field: sorting.value[0]?.id,
                sort_direction: sorting.value.length == 0 ? undefined : (sorting.value[0]?.desc ? "desc" : "asc"),
                ...filters // Include current filters
            },
            { preserveState: false, preserveScroll: true }
        );
    },
    onSortingChange: updaterOrValue => {
        if (typeof updaterOrValue === 'function') {
            sorting.value = updaterOrValue(sorting.value)
        } else {
            sorting.value = updaterOrValue
        }

        // Build filters object (same logic as above)
        let filters: Record<string, any> = {}
        if (columnFilters.value && columnFilters.value.length > 0) {
            filters = columnFilters.value.reduce((acc: Record<string, any>, filter) => {
                if (Array.isArray(filter.value) && filter.value.length > 0) {
                    acc[filter.id] = filter.value
                } else if (!Array.isArray(filter.value) && filter.value !== '' && filter.value !== null && filter.value !== undefined) {
                    acc[filter.id] = filter.value
                }
                return acc
            }, {})
        }

        router.get(
            route('records.index'),
            {
                page: 1, // Reset to first page when sorting changes
                per_page: pagination.value.pageSize,
                sort_field: sorting.value[0]?.id,
                sort_direction: sorting.value.length == 0 ? undefined : (sorting.value[0]?.desc ? "desc" : "asc"),
                ...filters
            },
            { preserveState: false, preserveScroll: true }
        );
    },
    onColumnFiltersChange: updaterOrValue => {
        if (typeof updaterOrValue === 'function') {
            columnFilters.value = updaterOrValue(columnFilters.value)
        } else {
            columnFilters.value = updaterOrValue
        }

        // Build filters object
        let filters: Record<string, any> = {}
        if (columnFilters.value && columnFilters.value.length > 0) {
            filters = columnFilters.value.reduce((acc: Record<string, any>, filter) => {
                // Handle array values (for multi-select filters)
                if (Array.isArray(filter.value) && filter.value.length > 0) {
                    acc[filter.id] = filter.value
                } else if (!Array.isArray(filter.value) && filter.value !== '' && filter.value !== null && filter.value !== undefined) {
                    acc[filter.id] = filter.value
                }
                return acc
            }, {})
        }

        router.get(
            route('records.index'),
            {
                page: 1, // Reset to first page when filtering
                per_page: pagination.value.pageSize,
                sort_field: sorting.value[0]?.id,
                sort_direction: sorting.value.length == 0 ? undefined : (sorting.value[0]?.desc ? "desc" : "asc"),
                ...filters
            },
            { preserveState: false, preserveScroll: true }
        );
    },
    onColumnVisibilityChange: updaterOrValue => {
        if (typeof updaterOrValue === 'function') {
            columnVisibility.value = updaterOrValue(columnVisibility.value)
        } else {
            columnVisibility.value = updaterOrValue
        }
    },
    onRowSelectionChange: updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
    onExpandedChange: updaterOrValue => valueUpdater(updaterOrValue, expanded),
    state: {
        get sorting() { return sorting.value },
        get columnFilters() { return columnFilters.value },
        get columnVisibility() { return columnVisibility.value },
        get rowSelection() { return rowSelection.value },
        get expanded() { return expanded.value },
        get pagination() { return pagination.value },
    },
})

// Local state for the input
const filterInput = ref<string>((table.getColumn('search')?.getFilterValue() as string) ?? '')// Function to apply the filter
const applyFilter = () => {
    table.getColumn('search')?.setFilterValue(filterInput.value)
}

const clearFilter = () => {
    filterInput.value = ''
    table.getColumn('search')?.setFilterValue('')
}

import Filter from './Filter.vue'
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

//Filter - Updated to use DDC Class
const filter_ddc_class = {
    title: 'Filter DDC Classes',
    column: 'ddc_class_id',
    data: props.ddcClasses.map(ddcClass => ({
        value: ddcClass.id.toString(),
        label: ddcClass.name,
        icon: h(ListFilter),
    }))
}

const filter_toolbar = [
    filter_ddc_class,
];

const showDialog = ref(false);
const showDialogCreate = () => {
    showDialog.value = true
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Records',
        href: '/records',
    },
];

</script>

<template>
    <Head title="Welcome" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="w-full">
                <div class="flex gap-2 items-center justify-between py-4">
                    <div class="flex gap-2">
                        <div class="relative">
                            <Input
                                class="w-[320px] pr-8"
                                placeholder="Search by acc. no., title ..."
                                v-model="filterInput"
                                @keyup.enter="applyFilter"
                                @blur="applyFilter"
                            />
                            <Button
                                v-if="filterInput"
                                variant="ghost"
                                class="absolute right-0 top-0 h-full px-2"
                                @click="clearFilter"
                            >
                                <X class="h-4 w-4" />
                            </Button>
                        </div>
                        <div v-for="filter in filter_toolbar" :key="filter.title">
                            <Filter :column="table.getColumn(filter.column)" :title="filter.title" :options="filter.data"></Filter>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <Button variant="outline" @click="showDialogCreate">
                            <Plus class="h-4"></Plus>
                            Create New
                        </Button>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="outline" class="ml-auto">
                                    Columns
                                    <ChevronDown class="ml-2 h-4 w-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuCheckboxItem v-for="column in
                                table.getAllColumns().filter((column) => column.getCanHide())"
                                                          :key="column.id" class="capitalize"
                                                          :checked="column.getIsVisible()"
                                                          @update:checked="(value: boolean | 'indeterminate') => {
                                                          column.toggleVisibility(!!value)
                                                        }">
                                    {{ column.id }}
                                </DropdownMenuCheckboxItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
                <div class="rounded-md border">
                    <Table class="w-full">
                        <TableHeader>
                            <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                                <TableHead v-for="header in headerGroup.headers" :key="header.id">
                                    <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <template v-if="table.getRowModel().rows?.length">
                                <template v-for="row in table.getRowModel().rows" :key="row.id">
                                    <TableRow :data-state="row.getIsSelected() && 'selected'">
                                        <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                            <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-if="row.getIsExpanded()">
                                        <TableCell :colspan="row.getAllCells().length">
                                            {{ JSON.stringify(row.original) }}
                                        </TableCell>
                                    </TableRow>
                                </template>
                            </template>

                            <TableRow v-else>
                                <TableCell :colspan="columns.length" class="h-24 text-center">
                                    No results.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
                <div class="flex items-center justify-end space-x-2 py-4">
                    <div class="flex-1 text-sm text-muted-foreground">
                        {{ table.getFilteredSelectedRowModel().rows.length }} of
                        {{ table.getFilteredRowModel().rows.length }} row(s) selected.
                    </div>
                    <div class="flex items-center space-x-2">
                        <p class="text-sm font-medium">Rows per page</p>
                        <Select :model-value="table.getState().pagination.pageSize.toString()" @update:model-value="(value) => table.setPageSize(Number(value))">
                            <SelectTrigger class="h-8 w-[70px]">
                                <SelectValue :placeholder="table.getState().pagination.pageSize.toString()" />
                            </SelectTrigger>
                            <SelectContent side="top">
                                <SelectItem v-for="pageSize in pageSizes" :key="pageSize" :value="pageSize.toString()">
                                    {{ pageSize }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="space-x-2">
                        <div class="flex items-center space-x-2">
                            <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex" :disabled="!table.getCanPreviousPage()" @click="table.setPageIndex(0)">
                                <DoubleArrowLeftIcon class="h-4 w-4" />
                            </Button>
                            <Button variant="outline" class="h-8 w-8 p-0" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">
                                <ChevronLeftIcon class="h-4 w-4" />
                            </Button>
                            <Button variant="outline" class="h-8 w-8 p-0" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
                                <ChevronRightIcon class="h-4 w-4" />
                            </Button>
                            <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex" :disabled="!table.getCanNextPage()" @click="table.setPageIndex(table.getPageCount() - 1)">
                                <DoubleArrowRightIcon class="h-4 w-4" />
                            </Button>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Dialog -->
        </div>
    </AppLayout>
</template>
