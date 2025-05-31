<template>
    <button
        @click="toggleTheme"
        class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition p-1"
        :title="theme === 'dark' ? 'Light mode' : 'Dark mode'"
    >
        <Sun v-if="theme === 'dark'" class="w-5 h-5" />
        <Moon v-else class="w-5 h-5" />
    </button>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Moon, Sun } from 'lucide-vue-next'

const theme = ref('light')

const applyTheme = (value) => {
    if (value === 'dark') {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }
    localStorage.setItem('theme', value)
    theme.value = value
}

const toggleTheme = () => {
    const newTheme = theme.value === 'dark' ? 'light' : 'dark'
    applyTheme(newTheme)
}

onMounted(() => {
    const savedTheme = localStorage.getItem('theme') || 'light'
    applyTheme(savedTheme)
})
</script>
