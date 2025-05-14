<template>
    <div class="max-w-md mx-auto mt-20 p-6 bg-white rounded shadow text-center">
        <h2 class="text-2xl font-bold mb-4">Verify your email</h2>
        <p class="mb-6">
            We’ve sent a verification link to your email. Please check your inbox and click the link to verify your account.
        </p>
        <button
            @click="resend"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            :disabled="loading"
        >
            <span v-if="loading">Sending...</span>
            <span v-else>Resend Email</span>
        </button>
        <p v-if="message" class="text-green-600 mt-4">{{ message }}</p>
        <p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from '@/utils/axios'

const loading = ref(false)
const message = ref('')
const error = ref('')

const resend = async () => {
    loading.value = true
    message.value = ''
    error.value = ''

    try {
        await axios.post('/email/verification-notification')
        message.value = 'Verification email resent.'
    } catch (e) {
        error.value = 'Something went wrong.'
    } finally {
        loading.value = false
    }
}
</script>
