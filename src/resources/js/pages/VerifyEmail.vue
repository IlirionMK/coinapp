<template>
    <section class="max-w-md w-full mx-auto mt-20 p-6 sm:p-8 bg-white dark:bg-gray-800 rounded-xl shadow text-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
            {{ t('verify.title') }}
        </h2>
        <p class="mb-6 text-gray-600 dark:text-gray-300">
            {{ t('verify.instructions') }}
        </p>
        <button
            @click="resend"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="loading"
        >
            <span v-if="loading">{{ t('verify.sending') }}</span>
            <span v-else>{{ t('verify.resend') }}</span>
        </button>
        <p v-if="message" class="text-green-600 dark:text-green-400 mt-4">{{ message }}</p>
        <p v-if="error" class="text-red-600 dark:text-red-400 mt-2">{{ error }}</p>
    </section>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from '@/utils/axios'

const { t } = useI18n()
const loading = ref(false)
const message = ref('')
const error = ref('')

const resend = async () => {
    loading.value = true
    message.value = ''
    error.value = ''
    try {
        const res = await axios.post('/email/verification-notification')
        message.value = res.data?.message || t('verify.resent')
    } catch (e) {
        if (e.response?.status === 400) {
            error.value = e.response.data.message || t('verify.already_verified')
        } else {
            error.value = t('verify.error')
        }
    } finally {
        loading.value = false
    }
}
</script>
