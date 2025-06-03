<template>
    <section class="max-w-md w-full mx-auto mt-12 p-6 sm:p-8 rounded-xl shadow-md bg-white dark:bg-gray-800">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-2">
            {{ $t('auth.reset_password') }}
        </h2>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('form.email') }}
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600
       rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white
       focus:outline-none focus:ring-2 focus:ring-blue-500"

                />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('form.password') }}
                </label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('form.password_confirmation') }}
                </label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <button
                type="submit"
                :disabled="isLoading"
                class="w-full py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700
                       dark:bg-blue-500 dark:hover:bg-blue-600
                       transition-colors duration-150 shadow-sm hover:shadow-md
                       disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="isLoading">{{ $t('auth.resetting') }}</span>
                <span v-else>{{ $t('auth.reset_password') }}</span>
            </button>
        </form>

        <Toast ref="toastRef" class="mt-6" />
    </section>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Toast from '@/components/ui/Toast.vue'
import { useI18n } from 'vue-i18n'

const route = useRoute()
const router = useRouter()
const { t } = useI18n()

const form = reactive({
    token: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const isLoading = ref(false)
const toastRef = ref()

onMounted(() => {
    form.token = route.params.token
})

const submit = async () => {
    isLoading.value = true
    try {
        await axios.post('/api/reset-password', form)
        toastRef.value?.show(t('auth.reset_success'), 'success')
        router.push('/login')
    } catch (error) {
        const message = error.response?.data?.message || t('auth.reset_failed')
        toastRef.value?.show(message, 'error')
    } finally {
        isLoading.value = false
    }
}
</script>
