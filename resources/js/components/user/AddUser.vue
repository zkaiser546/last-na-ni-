<script lang="ts">
import axios from 'axios';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';

export default {
    name: 'AddUser',
    // eslint-disable-next-line vue/no-reserved-component-names
    components: { Input, Label },
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
            this.loading = true;
            this.error = null;
            this.success = null;

            try {
                await axios.post('/api/users', this.form, {
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                    },
                });

                this.success = 'User added successfully!';
                this.form = { name: '', email: '', password: '' }; // Reset form
                route('users');
            } catch (error) {
                this.error = error.response?.data?.message || 'An error occurred while adding the user.';
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<template>
    <div class="add-user-form">
        <form @submit.prevent="addUser" class="space-y-4">
            <div>
                <Label for="name">Name</Label>
                <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Full name" />
            </div>
            <div>
                <Label for="name">Email</Label>
                <input v-model="form.email" type="email" id="email" class="w-full rounded border p-2 text-sm" placeholder="Enter email" required />
            </div>
            <div>
                <Label for="name">Password</Label>
                <input
                    v-model="form.password"
                    type="password"
                    id="password"
                    class="w-full rounded border p-2 text-sm"
                    placeholder="Enter password"
                    required
                />
            </div>
            <div>
                <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600" :disabled="loading">
                    {{ loading ? 'Adding...' : 'Add User' }}
                </button>
            </div>
            <div v-if="error" class="text-sm text-red-500">{{ error }}</div>
            <div v-if="success" class="text-sm text-green-500">{{ success }}</div>
        </form>
    </div>
</template>

<style scoped>
.add-user-form {
    max-width: 400px;
}
</style>
