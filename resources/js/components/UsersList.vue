<template>
    <div class="users-list">
        <div v-if="loading" class="text-gray-500">Loading users...</div>

        <div v-else-if="error" class="text-red-500">
            Error loading users: {{ error }}
        </div>

        <div v-else class="space-y-2">
            <div
                v-for="user in users"
                :key="user.id"
                class="p-3 border rounded bg-white"
            >
                <div class="font-medium">{{ user.name }}</div>
                <div class="text-sm text-gray-600">{{ user.email }}</div>
                <div class="text-xs text-gray-400">
                    Joined: {{ formatDate(user.created_at) }}
                </div>
            </div>

            <div v-if="users.length === 0" class="text-gray-500 text-center py-4">
                No users found
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'UsersList',
    data() {
        return {
            users: [],
            loading: true,
            error: null
        }
    },
    async mounted() {
        await this.fetchUsers()
    },
    methods: {
        async fetchUsers() {
            try {
                this.loading = true
                this.error = null

                const response = await fetch('/api/users')

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`)
                }

                this.users = await response.json()
            } catch (err) {
                this.error = err.message
                console.error('Error fetching users:', err)
            } finally {
                this.loading = false
            }
        },
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString()
        }
    }
}
</script>
