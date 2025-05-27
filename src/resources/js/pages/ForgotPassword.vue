<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">Forgot your password?</h2>
        <p class="text-sm text-gray-600 text-center">
            Enter your email and we’ll send you a password reset link.
        </p>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input
                    v-model="email"
                    type="email"
                    autocomplete="email"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <button
                type="submit"
                :disabled="isLoading"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="isLoading">Sending...</span>
                <span v-else>Send reset link</span>
            </button>
        </form>

        <Toast ref="toastRef" />
    </section>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import Toast from '@/components/ui/Toast.vue'

const email = ref('')
const isLoading = ref(false)
const toastRef = ref()

const submit = async () => {
    isLoading.value = true
    try {
        await axios.post('/api/forgot-password', { email: email.value })
        toastRef.value?.show('Password reset link sent to your email.', 'success')
    } catch (error) {
        const message = error.response?.data?.message || 'Failed to send reset link.'
        toastRef.value?.show(message, 'error')
    } finally {
        isLoading.value = false
    }
}
</script>
