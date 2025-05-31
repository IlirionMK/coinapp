<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">{{ $t('nav.register') }}</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">{{ $t('form.name') }}</label>
                <input v-model="form.name" type="text" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">{{ $t('form.email') }}</label>
                <input v-model="form.email" type="email" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">{{ $t('form.password') }}</label>
                <input v-model="form.password" type="password" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">{{ $t('form.password_confirmation') }}</label>
                <input v-model="form.password_confirmation" type="password"
                       class="w-full border rounded p-2" required />
            </div>

            <button type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                {{ $t('nav.register') }}
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
