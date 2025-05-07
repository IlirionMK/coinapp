<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">Register</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Name</label>
                <input v-model="name" type="text" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input v-model="email" type="email" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input v-model="password" type="password" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Confirm Password</label>
                <input v-model="confirm" type="password" class="w-full border rounded p-2" required />
            </div>

            <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                Register
            </button>
        </form>
    </section>
</template>

<script setup>
import axios from '@/utils/axios'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const password = ref('')
const confirm = ref('')
const error = ref('')
const router = useRouter()

const submit = async () => {
    error.value = ''
    try {
        await axios.get('/sanctum/csrf-cookie')
        await axios.post('/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: confirm.value,
        })
        router.push('/')
    } catch (err) {
        error.value = 'Registration failed'
    }
}
</script>
