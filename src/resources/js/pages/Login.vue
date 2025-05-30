<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">{{ $t('nav.login') }}</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input v-model="form.email" type="email" autocomplete="email"
                       class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input v-model="form.password" type="password" autocomplete="current-password"
                       class="w-full border rounded p-2" required />
            </div>

            <div class="text-right">
                <router-link to="/forgot-password" class="text-sm text-blue-600 hover:underline">
                    {{ $t('auth.forgot_password') }}
                </router-link>
            </div>

            <button type="submit" :disabled="isLoading"
                    class="w-full bg-blue-600 text-white py-2 rounded
                     hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                <span v-if="isLoading">{{ $t('auth.logging_in') }}</span>
                <span v-else>{{ $t('nav.login') }}</span>
            </button>
        </form>

        <Toast ref="toastRef" />
    </section>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import useUser from '@/stores/user'
import Toast from '@/components/ui/Toast.vue'

const { login, fetchUser } = useUser()
const router = useRouter()
const { t } = useI18n()
const toastRef = ref()

const form = reactive({ email: '', password: '' })
const isLoading = ref(false)

const submit = async () => {
    isLoading.value = true
    try {
        await login(form)
        await fetchUser()
        toastRef.value?.show(t('auth.login_success'), 'success')

        const redirect = localStorage.getItem('logoutRedirectPath') || '/dashboard'
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
