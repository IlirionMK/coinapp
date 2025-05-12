<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">Register</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Name</label>
                <input v-model="form.name" type="text" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input v-model="form.email" type="email" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input v-model="form.password" type="password" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Confirm Password</label>
                <input v-model="form.password_confirmation" type="password" class="w-full border rounded p-2" required />
            </div>

            <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                Register
            </button>
        </form>
    </section>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import useUser from '@/stores/user'

const { register, error } = useUser()
const router = useRouter()

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    register(form, router)
}
</script>
