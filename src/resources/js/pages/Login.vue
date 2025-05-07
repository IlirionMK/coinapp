<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">Login</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input v-model="email" type="email" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input v-model="password" type="password" class="w-full border rounded p-2" required />
            </div>

            <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Login
            </button>
        </form>
    </section>
</template>

<script setup>
import axios from '@/utils/axios'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const submit = async () => {
    error.value = ''
    try {
        await axios.get('/sanctum/csrf-cookie')
        await axios.post('/login', { email: email.value, password: password.value })
        router.push('/')
    } catch (err) {
        error.value = 'Login failed'
    }
}
</script>
