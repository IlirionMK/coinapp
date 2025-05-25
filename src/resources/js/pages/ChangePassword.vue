<template>
    <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-6">{{ $t('profile.change_password') }}</h1>

        <form @submit.prevent="submit">
            <div v-if="errors.length" class="mb-4 text-sm text-red-600 space-y-1">
                <div v-for="(error, index) in errors" :key="index">{{ error }}</div>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">{{ $t('profile.current_password') }}</label>
                <input
                    v-model="form.current_password"
                    type="password"
                    class="w-full p-2 border rounded"
                    required
                />
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">{{ $t('profile.new_password') }}</label>
                <input
                    v-model="form.new_password"
                    type="password"
                    class="w-full p-2 border rounded"
                    required
                />
            </div>

            <div class="mb-6">
                <label class="block font-medium mb-1">{{ $t('profile.new_password_confirmation') }}</label>
                <input
                    v-model="form.new_password_confirmation"
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
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const form = ref({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
})

const errors = ref([])

const submit = async () => {
    errors.value = []
    try {
        await axios.put('/api/profile/password', {
            current_password: form.value.current_password,
            new_password: form.value.new_password,
            new_password_confirmation: form.value.new_password_confirmation
        })
        alert('✔ ' + $t('profile.password_updated'))
        form.value.current_password = ''
        form.value.new_password = ''
        form.value.new_password_confirmation = ''
    } catch (e) {
        console.error('Password update error:', e)
        if (e.response?.status === 422) {
            const data = e.response.data
            if (data.errors) {
                errors.value = Object.values(data.errors).flat()
            } else if (data.message) {
                errors.value = [data.message]
            }
        } else {
            errors.value = [$t('error.unknown')]
        }
    }
}
</script>
