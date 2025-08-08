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
    useVueTable,
} from '@tanstack/vue-table'
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next'

import { h, ref } from 'vue'
import DropdownAction from './DataTableDemoColumn.vue'
import { Checkbox } from '@/components/ui/checkbox'
import { Badge } from '@/components/ui/badge'
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

import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner'

interface Props {
    data?: {
        data: any[]
        current_page?: number
        per_page?: number
        last_page?: number
    }
    filter?: any[]
}

const props = withDefaults(defineProps<Props>(), {
    data: () => ({ data: [], current_page: 1, per_page: 10, last_page: 1 }),
    filter: () => []
})

import type { Table, Row, Column, SortingState, ColumnFiltersState } from '@tanstack/vue-table'
type RowData = any
const data = props.data.data; // Now safe to access directly
const columns = [
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
        accessorKey: 'name',
        header: ({ column }: { column: Column<RowData, any> }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }: { row: Row<RowData> }) => h('div', { class: 'lowercase' }, row.getValue('name')),
    },
    {
        accessorKey: 'title',
        header: ({ column }: { column: Column<RowData, any> }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Title', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }: { row: Row<RowData> }) => h('div', { class: 'lowercase' }, row.getValue('title')),
    },
    {
        accessorKey: 'price',
        header: () => h('div', { class: 'text-right' }, 'Price'),
        cell: ({ row }: { row: Row<RowData> }) => {
            const amount = Number.parseFloat(row.getValue('price'))

            // Format the amount as a dollar amount
            const formatted = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            }).format(amount)

            return h('div', { class: 'text-right font-medium' }, formatted)
        },
    },
    {
        accessorKey: 'is_active',
        header: 'Status',
        cell: ({ row }: { row: Row<RowData> }) => {
            const status = row.getValue('is_active');

            if (status) {
                return h('div', h(Badge, 'Active'))
            } else {
                return h('div', h(Badge, { variant: 'outline' }, 'Inactive'))
            }
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }: { row: Row<RowData> }) => {
            const payment = row.original

            return h('div', { class: 'relative' }, h(DropdownAction, {
                payment,
                onEdit,
                onExpand: row.toggleExpanded,
            }))
        },
    },
]

const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>(props.filter ?? [])
const columnVisibility = ref({})
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
    onPaginationChange: updater => {
        if (typeof updater === 'function') {
            pagination.value = updater(pagination.value);
        } else {
            pagination.value = updater;
        }
        router.get(
            route('product.index'),
            {
                page: pagination.value.pageIndex + 1,
                per_page: pagination.value.pageSize,
                sort_field: sorting.value[0]?.id,
                sort_direction: sorting.value.length == 0 ? undefined : (sorting.value[0]?.desc ? "desc" : "asc"),
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
        let filters: Record<string, any> = {};
        if (columnFilters.value) {
            filters = columnFilters.value.reduce((acc, filter) => {
                acc[filter.id] = filter.value
                return acc
            }, {})
        }
        router.get(
            route('product.index'),
            {
                page: pagination.value.pageIndex + 1,
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
        let filters = {};
        if (columnFilters.value) {
            filters = columnFilters.value.reduce((acc: Record<string, any>, filter) => {
                acc[filter.id] = filter.value
                return acc
            }, {})
        }
        router.get(
            route('product.index'),
            {
                page: pagination.value.pageIndex + 1,
                per_page: pagination.value.pageSize,
                sort_field: sorting.value[0]?.id,
                sort_direction: sorting.value.length == 0 ? undefined : (sorting.value[0]?.desc ? "desc" : "asc"),
                ...filters

            },
            { preserveState: false, preserveScroll: true }
        );
    },
    onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
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

import {
    CheckCircledIcon,
    MinusCircledIcon,
} from "@radix-icons/vue";
import Filter from './Filter.vue'
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
//Filter
const filter_status = {
    title: 'Filter Status',
    column: 'is_active',
    data: [
        {
            value: "1",
            label: "Active",
            icon: h(CheckCircledIcon),
        },
        {
            value: "0",
            label: "Inactive",
            icon: h(MinusCircledIcon),
        },
    ]
}

const filter_toolbar = [
    filter_status,
];



const showDialog = ref(false);
const showDialogCreate = () => {
    showDialog.value = true
}

const imageUrl = ref([]);
const productSize = ref(['20 ml', ' 50ml']);

const form = useForm({
    name: '',
    title: '',
    description: '',
    category_ref_id: null,
    brand_ref_id: null,
    price: 0,
    discount_price: 0,
    benefits_content: '',
    ingredients_content: '',
    howtouse_content: '',
    product_size_id: '',
    is_active: 1,
    image: imageUrl.value
})

const errors = ref({});

const submit = () => {
    form.product_size_id = productSize.value.toString()
    form.image = imageUrl.value
    //form here
    form.post('/products', {
        preserveState: true,
        onError: (error) => {
            //show error
            errors.value = error;
        },
        onSuccess: () => {
            //show success
            toast('Event has been created', {
                description: 'Sunday, December 03, 2023 at 9:00 AM',
                action: {
                    label: 'Undo',
                    onClick: () => console.log('Undo'),
                },
            })

            //Refresh Data
            showDialog.value = false
            setTimeout(() => {
                window.location.reload()
            }, 3000)
        }
    })

}

const openWidget = () => {
    myWidget.open();
}


const onEdit = async (id) => {
    console.log(id);
    // showDialog.value = true
    try {
        const res = await fetch(`/products/${id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        if (!res.ok) {
            console.error('Error ');
        }
        const data = await res.json();
        console.log(data)

        // Set to form
        form.name = data.data.name;
        form.price = data.data.price;
        form.discount_price = data.data.discount_price;
        form.howtouse_content = data.data.howtouse_content;
        form.benefits_content = data.data.benefits_content;
        form.description = data.data.description;
        form.category_ref_id = data.data.category_ref_id?.toString();
        form.ingredients_content = data.data.ingredients_content;
        form.brand_ref_id = data.data.brand_ref_id?.toString();
        form.is_active = data.data.is_active;
        form.title = data.data.title;
        form.product_size_id = data.data.product_size_id;

        const imageUrls = data.data.image.map((e) => e.url);
        imageUrl.value = imageUrls

        //Open Dialog
        showDialog.value = true

    } catch (error) {
        console.error(error);
    }
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

        <template #title>
            Product Page
        </template>
        <div class="p-4">
            <div class="w-full">
                <div class="flex gap-2 items-center justify-between py-4">
                    <div class="flex gap-2">
                        <Input class="max-w-sm" placeholder="Filter name..." :model-value="table.getColumn('name')?.getFilterValue()" @update:model-value=" table.getColumn('name')?.setFilterValue($event)" />
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
                                <DropdownMenuCheckboxItem v-for="column in table.getAllColumns().filter((column) => column.getCanHide())" :key="column.id" class="capitalize" :checked="column.getIsVisible()" @update:checked="(value) => {
                  column.toggleVisibility(!!value)
                }">
                                    {{ column.id }}
                                </DropdownMenuCheckboxItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
                <div class="rounded-md border">
                    <Table>
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
                test git on users x products
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
