<template>
    <section class="min-h-screen flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 text-center p-6">
        <h1 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-4">
            {{ t('session.expired_title') }}
        </h1>
        <p class="text-gray-700 dark:text-gray-300 mb-6">
            {{ t('session.expired_message') }}
        </p>

        <RouterLink
            to="/login"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded transition duration-150 text-sm"
        >
            {{ t('session.go_to_login') }}
        </RouterLink>

        <RouterLink
            v-if="previousPath"
            :to="previousPath"
            class="mt-4 text-sm text-blue-600 dark:text-blue-400 underline hover:no-underline"
        >
            {{ t('session.go_back') }}
        </RouterLink>

        <RouterLink
            to="/"
            class="mt-2 text-sm text-gray-500 dark:text-gray-400 hover:underline"
        >
            {{ t('session.homepage') }}
        </RouterLink>

        <Toast ref="toastRef" class="mt-6" />
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import rawAxios from 'axios'
import Toast from '@/components/ui/Toast.vue'

const previousPath = ref(null)
const toastRef = ref()
const { t } = useI18n()

onMounted(async () => {
    toastRef.value?.show(t('session.logged_out'), 'info')

    const path = localStorage.getItem('logoutRedirectPath')
    if (path && path !== '/session-expired' && path !== '/login') {
        previousPath.value = path
    }

    await rawAxios.get('/sanctum/csrf-cookie', { withCredentials: true })
})
</script>
