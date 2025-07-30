<template>
    <div>
        <h1>{{ message }}</h1>
<button @click="fetchData">Fetch Data</button>
<p>{{ apiResponse }}</p>
</div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import axios from 'axios';

export default defineComponent({
    name: 'HelloWorld',
    data() {
        return {
            message: 'Hello, Vue + Laravel!',
            apiResponse: '',
        };
    },
    methods: {
        async fetchData() {
            try {
                const response = await axios.get('/api/sample');
                this.apiResponse = response.data.message;
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
    },
});
</script>

<style scoped>
h1 {
    color: #2c3e50;
}
</style>
