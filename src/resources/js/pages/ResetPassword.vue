<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">Reset your password</h2>

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
                <label class="block text-sm font-medium">New password</label>
                <input
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <div>
                <label class="block text-sm font-medium">Confirm password</label>
                <input
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <button
                type="submit"
                :disabled="isLoading"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="isLoading">Resetting...</span>
                <span v-else>Reset Password</span>
            </button>
        </form>

        <Toast ref="toastRef" />
    </section>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import Toast from '@/components/ui/Toast.vue'

const route = useRoute()
const router = useRouter()

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
        toastRef.value?.show('Password reset successfully. You can now log in.', 'success')
        router.push('/login')
    } catch (error) {
        const message = error.response?.data?.message || 'Password reset failed.'
        toastRef.value?.show(message, 'error')
    } finally {
        isLoading.value = false
    }
}
</script>
