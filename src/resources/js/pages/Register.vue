<template>
    <section class="max-w-md w-full mx-auto mt-12 p-6 sm:p-8 rounded-xl shadow-md bg-white dark:bg-gray-800">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-4">
            {{ $t('nav.register') }}
        </h2>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('form.name') }}
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ $t('form.email') }}
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="email"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
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
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
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
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                />
            </div>

            <button
                type="submit"
                class="w-full py-2 rounded-md text-white bg-green-600 hover:bg-green-700
                       dark:bg-green-500 dark:hover:bg-green-600
                       transition-colors duration-150 shadow-sm hover:shadow-md
                       disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ $t('nav.register') }}
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

const { register } = useUser()
const router = useRouter()
const toastRef = ref()
const { t } = useI18n()

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = async () => {
    try {
        await register(form)
        toastRef.value?.show(t('auth.register_success'), 'success')
        await router.push('/verify-email')
    } catch (e) {
        let message = t('auth.register_error')
        if (e.response?.data?.errors?.email?.[0]) {
            message = e.response.data.errors.email[0]
        }
        toastRef.value?.show(message, 'error')
        form.password = ''
        form.password_confirmation = ''
    }
}
</script>
