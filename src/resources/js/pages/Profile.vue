<template>
    <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-xl shadow space-y-10">
        <section>
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

        <section>
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
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

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
        alert('✔ ' + t('profile.updated'))
    } catch (e) {
        console.error(e)
        if (e.response?.status === 422) {
            const data = e.response.data
            if (data.errors) {
                updateErrors.value = Object.values(data.errors).flat()
            } else if (data.message) {
                updateErrors.value = [data.message]
            }
        } else {
            updateErrors.value = [t('error.unknown')]
        }
    }
}

const updatePassword = async () => {
    passwordErrors.value = []
    try {
        await axios.put('/api/profile/password', passwordForm.value)
        alert('✔ ' + t('profile.password_updated'))
        passwordForm.value.current_password = ''
        passwordForm.value.new_password = ''
        passwordForm.value.new_password_confirmation = ''
    } catch (e) {
        console.error(e)
        if (e.response?.status === 422) {
            const data = e.response.data
            if (data.errors) {
                passwordErrors.value = Object.values(data.errors).flat()
            } else if (data.message) {
                passwordErrors.value = [data.message]
            }
        } else {
            passwordErrors.value = [t('error.unknown')]
        }
    }
}
</script>
