<template>
    <div
        v-if="visible"
        :class="toastClasses"
        class="fixed top-4 right-4 px-4 py-2 rounded shadow-lg z-50 transition-opacity"
    >
        {{ message }}
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const visible = ref(false)
const message = ref('')
const type = ref('info') // success | error | info | warning

function show(msg, variant = 'info', duration = 3000) {
    message.value = msg
    type.value = variant
    visible.value = true
    setTimeout(() => visible.value = false, duration)
}

const toastClasses = computed(() => {
    return {
        'bg-green-600 text-white': type.value === 'success',
        'bg-red-600 text-white': type.value === 'error',
        'bg-blue-600 text-white': type.value === 'info',
        'bg-yellow-500 text-black': type.value === 'warning',
    }
})

defineExpose({ show })
</script>
