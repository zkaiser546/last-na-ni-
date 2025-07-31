<script lang="ts">

import { defineComponent } from 'vue';

// Define the User interface
interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
}

export default defineComponent({
    name: 'UsersList',
    data() {
        return {
            users: [] as User[], // Type the users array
            loading: true as boolean,
            error: null as string | null
        };
    },
    async mounted() {
        await this.fetchUsers();
    },
    methods: {
        async fetchUsers() {
            try {
                this.loading = true;
                this.error = null;

                const response = await fetch('/api/users');

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                this.users = await response.json();
            } catch (err: any) { // Explicitly type err as any or use a more specific Error type
                this.error = err.message;
                console.error('Error fetching users:', err);
            } finally {
                this.loading = false;
            }
        },
        formatDate(dateString: string): string { // Add type for parameter and return value
            return new Date(dateString).toLocaleDateString();
        }
    }
});
</script>

<template>
    <div class="users-list">
        <div v-if="loading" class="text-gray-500 text-center py-4">
            Loading users...
        </div>

        <div v-else-if="error" class="text-red-500 text-center py-4">
            Error loading users: {{ error }}
        </div>

        <div v-else>
            <table class="w-full border-collapse">
                <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-left border-b">Name</th>
                    <th class="p-3 text-left border-b">Email</th>
                    <th class="p-3 text-left border-b">Joined</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="user in users"
                    :key="user.id"
                    class="border-b hover:bg-gray-50"
                >
                    <td class="p-3 font-medium">{{ user.name }}</td>
                    <td class="p-3 text-gray-600">{{ user.email }}</td>
                    <td class="p-3 text-gray-400 text-sm">
                        {{ formatDate(user.created_at) }}
                    </td>
                </tr>
                <tr v-if="users.length === 0">
                    <td colspan="3" class="text-gray-500 text-center py-4">
                        No users found
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
