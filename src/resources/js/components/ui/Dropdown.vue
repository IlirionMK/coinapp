<template>
    <div ref="dropdownRef" class="relative">
        <slot name="trigger" :toggle="toggle" />

        <div
            v-if="isOpen"
            class="absolute left-0 mt-2 w-[400px] max-w-[90vw] bg-white border dark:bg-gray-800 dark:border-gray-600 rounded shadow z-50"
        >
        <slot />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const isOpen = ref(false)
const dropdownRef = ref(null)

const toggle = () => (isOpen.value = !isOpen.value)

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isOpen.value = false
    }
}

const handleEscape = (event) => {
    if (event.key === 'Escape') {
        isOpen.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
    document.addEventListener('keydown', handleEscape)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
    document.removeEventListener('keydown', handleEscape)
})
</script>
