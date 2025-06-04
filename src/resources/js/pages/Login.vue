<template>
    <section class="max-w-md w-full mx-auto mt-12 p-6 sm:p-8 rounded-xl shadow-md bg-white dark:bg-gray-800">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-4">
            {{ $t('nav.login') }}
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
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                    autocomplete="current-password"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <div class="text-right">
                <router-link
                    to="/forgot-password"
                    class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                >
                    {{ $t('auth.forgot_password') }}
                </router-link>
            </div>

            <button
                type="submit"
                :disabled="isLoading"
                class="w-full py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700
                       dark:bg-blue-500 dark:hover:bg-blue-600
                       transition-colors duration-150 shadow-sm hover:shadow-md
                       disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="isLoading">{{ $t('auth.logging_in') }}</span>
                <span v-else>{{ $t('nav.login') }}</span>
            </button>
        </form>

        <Toast ref="toastRef" class="mt-6" />
    </section>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import useUser from '@/stores/user'
import Toast from '@/components/ui/Toast.vue'

const { login, user } = useUser()
const router = useRouter()
const { t } = useI18n()
const toastRef = ref()

const form = reactive({ email: '', password: '' })
const isLoading = ref(false)

const submit = async () => {
    isLoading.value = true
    try {
        await login(form)

        toastRef.value?.show(t('auth.login_success'), 'success')

        let redirect = localStorage.getItem('logoutRedirectPath')
        if (!redirect) {
            redirect = user.value?.role === 'admin' ? '/admin' : '/dashboard'
        }
        localStorage.removeItem('logoutRedirectPath')
        await router.push(redirect)
    } catch (e) {
        let message = t('auth.login_error')
        if (e.response?.data?.errors?.email?.[0]) {
            message = e.response.data.errors.email[0]
        }
        toastRef.value?.show(message, 'error')
        form.password = ''
    } finally {
        isLoading.value = false
    }
}
</script>
