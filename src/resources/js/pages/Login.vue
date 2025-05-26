<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">Login</h2>

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
                <label class="block text-sm font-medium">Password</label>
                <input
                    v-model="form.password"
                    type="password"
                    autocomplete="current-password"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <button
                type="submit"
                :disabled="isLoading"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="isLoading">Logging in...</span>
                <span v-else>Login</span>
            </button>
        </form>

        <Toast ref="toastRef" />
    </section>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import useUser from '@/stores/user'
import Toast from '@/components/ui/Toast.vue'

const { login } = useUser()
const router = useRouter()
const toastRef = ref()

const form = reactive({
    email: '',
    password: '',
})

const isLoading = ref(false)

const submit = async () => {
    isLoading.value = true
    try {
        await login(form, router)
        toastRef.value?.show('Logged in successfully.', 'success')
        await router.push('/')
    } catch (e) {
        toastRef.value?.show('Invalid credentials or server error.', 'error')
    } finally {
        isLoading.value = false
    }
}
</script>
