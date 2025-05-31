<template>
    <main class="min-h-screen bg-gray-100 py-10 px-4">
        <div class="max-w-xl mx-auto space-y-10">
            <div class="flex justify-between items-center">
                <RouterLink
                    to="/dashboard"
                    class="text-blue-600 hover:underline text-sm"
                >
                    ← {{ $t('profile.back_to_dashboard') }}
                </RouterLink>

                <LanguageSwitcher />
            </div>

            <section class="bg-white p-6 rounded-xl shadow">
                <h1 class="text-2xl font-bold mb-6">{{ $t('profile.title') }}</h1>

                <form @submit.prevent="updateProfile">
                    <div v-if="updateErrors.length" class="mb-4 text-sm text-red-600 space-y-1">
                        <div v-for="(error, index) in updateErrors" :key="index">{{ error }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ $t('profile.name') }}</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ $t('profile.email') }}</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>

                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        {{ $t('profile.save') }}
                    </button>
                </form>
            </section>

            <section class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-xl font-semibold mb-6">{{ $t('profile.change_password') }}</h2>

                <form @submit.prevent="updatePassword">
                    <div v-if="passwordErrors.length" class="mb-4 text-sm text-red-600 space-y-1">
                        <div v-for="(error, index) in passwordErrors" :key="index">{{ error }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ $t('profile.current_password') }}</label>
                        <input
                            v-model="passwordForm.current_password"
                            type="password"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ $t('profile.new_password') }}</label>
                        <input
                            v-model="passwordForm.new_password"
                            type="password"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium mb-1">{{ $t('profile.new_password_confirmation') }}</label>
                        <input
                            v-model="passwordForm.new_password_confirmation"
                            type="password"
                            class="w-full p-2 border rounded"
                            required
                        />
                    </div>

                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        {{ $t('profile.save_password') }}
                    </button>
                </form>
            </section>

            <section class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-xl font-semibold mb-4 text-red-600">
                    {{ $t('profile.delete_account') }}
                </h2>
                <p class="mb-4 text-gray-700">{{ $t('profile.delete_account_desc') }}</p>

                <button
                    @click="confirmDelete"
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                >
                    {{ $t('profile.delete_account_button') }}
                </button>
            </section>
        </div>

        <Toast ref="toast" />
        <ConfirmDeleteModal ref="confirmDeleteModal" />
    </main>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useI18n } from 'vue-i18n'
import { useRouter, RouterLink } from 'vue-router'
import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'
import Toast from '@/components/ui/Toast.vue'
import ConfirmDeleteModal from '@/components/ui/ConfirmDeleteModal.vue'
import useUser from '@/stores/user'

const { t } = useI18n()
const router = useRouter()
const toast = ref(null)
const confirmDeleteModal = ref(null)
const { logout } = useUser()

const form = ref({
    name: '',
    email: ''
})
const updateErrors = ref([])

const passwordForm = ref({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
})
const passwordErrors = ref([])

onMounted(async () => {
    const { data } = await axios.get('/api/profile')
    form.value.name = data.name
    form.value.email = data.email
})

const updateProfile = async () => {
    updateErrors.value = []
    try {
        await axios.put('/api/profile', form.value)
        toast.value.show(t('profile.updated'), 'success')
    } catch (e) {
        if (e.response?.status === 422) {
            const data = e.response.data
            updateErrors.value = data.errors
                ? Object.values(data.errors).flat()
                : [data.message]
        } else {
            updateErrors.value = [t('error.unknown')]
        }
    }
}

const updatePassword = async () => {
    passwordErrors.value = []
    try {
        await axios.put('/api/profile/password', passwordForm.value)
        toast.value.show(t('profile.password_updated'), 'success')
        passwordForm.value.current_password = ''
        passwordForm.value.new_password = ''
        passwordForm.value.new_password_confirmation = ''
    } catch (e) {
        if (e.response?.status === 422) {
            const data = e.response.data
            passwordErrors.value = data.errors
                ? Object.values(data.errors).flat()
                : [data.message]
        } else {
            passwordErrors.value = [t('error.unknown')]
        }
    }
}

const confirmDelete = () => {
    confirmDeleteModal.value.show(async (password) => {
        try {
            await axios.delete('/api/profile', {
                data: { password },
                withCredentials: true,
            })
            toast.value.show(t('profile.account_deleted'), 'success')
            await logout(router)
            await router.push('/login')
        } catch (e) {
            if (e.response?.status === 422) {
                const message = e.response.data.errors?.password?.[0] || e.response.data.message
                toast.value.show(message || t('error.unknown'), 'error')
            } else {
                toast.value.show(t('error.unknown'), 'error')
            }
        }
    })
}
</script>
