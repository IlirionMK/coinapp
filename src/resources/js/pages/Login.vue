<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">Login</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input
                    v-model="form.password"
                    type="password"
                    autocomplete="current-password"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

            <button
                type="submit"
                :disabled="isLoading"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="isLoading">Logging in...</span>
                <span v-else>Login</span>
            </button>
        </form>
    </section>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import useUser from '@/stores/user'

const { login, error } = useUser()
const router = useRouter()

const form = reactive({
    email: '',
    password: '',
})

const isLoading = ref(false)

const submit = async () => {
    isLoading.value = true
    try {
        await login(form, router)
    } finally {
        isLoading.value = false
    }
}
</script>
