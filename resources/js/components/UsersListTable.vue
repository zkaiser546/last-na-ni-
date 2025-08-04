<script setup lang="ts">

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import PaginationLinks from '@/components/PaginationLinks.vue';

defineProps({
    users: Object,
});
</script>

<template>
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
            <TableRow v-for="user in users.data" :key="user.id">
                <TableCell >
                    {{ user.first_name }}
                </TableCell>
                <TableCell >
                    {{ user.last_name }}
                </TableCell>
                <TableCell >
                    {{ user.email }}
                </TableCell>
                <TableCell >
                    {{ user.user_type.name }}
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>

    <PaginationLinks :paginator="users" />

</template>

<style scoped>

</style>
