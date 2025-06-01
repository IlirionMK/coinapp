<template>
    <main class="min-h-screen bg-gray-100 dark:bg-gray-900 py-10 px-4 transition-colors">
        <div class="max-w-xl mx-auto space-y-10">
            <div class="mb-4">
                <RouterLink
                    to="/dashboard"
                    class="text-blue-600 dark:text-blue-400 hover:underline text-sm"
                >
                    ← {{ $t('profile.back_to_dashboard') }}
                </RouterLink>
            </div>

            <section class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md transition-colors">
                <h1 class="text-2xl font-bold mb-6 text-center text-gray-900 dark:text-white">
                    {{ $t('profile.title') }}
                </h1>

                <form @submit.prevent="updateProfile" class="space-y-4">
                    <div v-if="updateErrors.length" class="text-sm text-red-600 space-y-1 text-center">
                        <div v-for="(error, index) in updateErrors" :key="index">{{ error }}</div>
                    </div>

                    <div>
                        <label class="block font-medium mb-1 dark:text-white text-center sm:text-left">
                            {{ $t('profile.name') }}
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full p-2 rounded bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-center sm:text-left transition-colors"
                            required
                        />
                    </div>

                    <div>
                        <label class="block font-medium mb-1 dark:text-white text-center sm:text-left">
                            {{ $t('profile.email') }}
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full p-2 rounded bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-center sm:text-left transition-colors"
                            required
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors"
                    >
                        {{ $t('profile.save') }}
                    </button>
                </form>
            </section>

            <section class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md transition-colors">
                <h2 class="text-xl font-semibold mb-6 text-center text-gray-900 dark:text-white">
                    {{ $t('profile.change_password') }}
                </h2>

                <form @submit.prevent="updatePassword" class="space-y-4">
                    <div v-if="passwordErrors.length" class="text-sm text-red-600 space-y-1 text-center">
                        <div v-for="(error, index) in passwordErrors" :key="index">{{ error }}</div>
                    </div>

                    <div>
                        <label class="block font-medium mb-1 dark:text-white text-center sm:text-left">
                            {{ $t('profile.current_password') }}
                        </label>
                        <input
                            v-model="passwordForm.current_password"
                            type="password"
                            class="w-full p-2 rounded bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-center sm:text-left transition-colors"
                            required
                        />
                    </div>

                    <div>
                        <label class="block font-medium mb-1 dark:text-white text-center sm:text-left">
                            {{ $t('profile.new_password') }}
                        </label>
                        <input
                            v-model="passwordForm.new_password"
                            type="password"
                            class="w-full p-2 rounded bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-center sm:text-left transition-colors"
                            required
                        />
                    </div>

                    <div>
                        <label class="block font-medium mb-1 dark:text-white text-center sm:text-left">
                            {{ $t('profile.new_password_confirmation') }}
                        </label>
                        <input
                            v-model="passwordForm.new_password_confirmation"
                            type="password"
                            class="w-full p-2 rounded bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-center sm:text-left transition-colors"
                            required
                        />
                    </div>

                    <button
                        type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors"
                    >
                        {{ $t('profile.save_password') }}
                    </button>
                </form>
            </section>

            <section class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md transition-colors">
                <h2 class="text-xl font-semibold mb-4 text-center text-red-600 dark:text-red-400">
                    {{ $t('profile.delete_account') }}
                </h2>
                <p class="mb-4 text-center text-gray-700 dark:text-gray-300">
                    {{ $t('profile.delete_account_desc') }}
                </p>

                <div class="flex justify-center">
                    <button
                        @click="confirmDelete"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition-colors"
                    >
                        {{ $t('profile.delete_account_button') }}
                    </button>
                </div>
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
import Toast from '@/components/ui/Toast.vue'
import ConfirmDeleteModal from '@/components/ui/ConfirmDeleteModal.vue'

const { t } = useI18n()
const router = useRouter()

const toast = ref(null)
const confirmDeleteModal = ref(null)

const form = ref({ name: '', email: '' })
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
