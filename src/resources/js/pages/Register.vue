<template>
    <section class="max-w-md mx-auto mt-12 space-y-6 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-center">Register</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Name</label>
                <input v-model="form.name" type="text" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input v-model="form.email" type="email" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input v-model="form.password" type="password" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Confirm Password</label>
                <input v-model="form.password_confirmation" type="password" class="w-full border rounded p-2" required />
            </div>

            <button
                type="submit"
                class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700"
            >
                Register
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

const { register } = useUser()
const router = useRouter()
const toastRef = ref()

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = async () => {
    try {
        await register(form, router)
        toastRef.value?.show('Registration successful. Please verify your email.', 'success')
        await router.push('/')
    } catch (e) {
        toastRef.value?.show('Registration failed. Please check your input.', 'error')
    }
}
</script>
