<template>
    <div v-if="visible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-xl w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4 text-red-600">
                {{ $t('profile.confirm_delete_title') }}
            </h2>
            <p class="mb-4 text-gray-700">{{ $t('profile.confirm_delete_desc') }}</p>

            <input
                v-model="password"
                type="password"
                :placeholder="$t('profile.password_placeholder')"
                class="w-full p-2 border rounded mb-4"
            />

            <div class="flex justify-end gap-2">
                <button @click="cancel" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    {{ $t('cancel') }}
                </button>
                <button @click="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    {{ $t('delete') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineExpose } from 'vue'

const visible = ref(false)
const password = ref('')
let onConfirm = null
let onError = null

function show(callback, errorCallback = null) {
    visible.value = true
    password.value = ''
    onConfirm = callback
    onError = errorCallback
}

function cancel() {
    visible.value = false
}

function submit() {
    if (!password.value.trim()) {
        onError?.('Password is required')
        return
    }

    visible.value = false
    onConfirm?.(password.value)
}

defineExpose({ show })
</script>
