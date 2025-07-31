<script lang="ts">

export default {
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
            },
            loading: false,
            error: null,
            success: null,
        };
    },
    methods: {
        async addUser() {
            console.log('Form data:', this.form); // Debug
            this.loading = true;
            this.error = null;
            this.success = null;

            try {
                // Fetch CSRF cookie
                await axios.get('http://localhost:8000/sanctum/csrf-cookie'); // Adjust URL to your backend
                const response = await axios.post('http://localhost:8000/api/users', this.form, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`, // Ensure token is sent
                        Accept: 'application/json',
                    },
                });
                this.success = 'User added successfully!';
                this.form = { name: '', email: '', password: '' };
                console.log(response);
            } catch (error) {
                console.error('Error:', error.response); // Debug
                this.error = error.response?.data?.message || 'Failed to add user.';
            } finally {
                this.loading = false;
            }
        }
    },
};
</script>

<template>
    <div class="add-user-form">
        <form @submit.prevent="addUser" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    class="w-full border rounded p-2 text-sm"
                    placeholder="Enter name"
                    required
                />
            </div>
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input
                    v-model="form.email"
                    type="email"
                    id="email"
                    class="w-full border rounded p-2 text-sm"
                    placeholder="Enter email"
                    required
                />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium">Password</label>
                <input
                    v-model="form.password"
                    type="password"
                    id="password"
                    class="w-full border rounded p-2 text-sm"
                    placeholder="Enter password"
                    required
                />
            </div>
            <div>
                <button
                    type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    :disabled="loading"
                >
                    {{ loading ? 'Adding...' : 'Add User' }}
                </button>
            </div>
            <div v-if="error" class="text-red-500 text-sm">{{ error }}</div>
            <div v-if="success" class="text-green-500 text-sm">{{ success }}</div>
        </form>
    </div>
</template>

<style scoped>
.add-user-form {
    max-width: 400px;
}
</style>
