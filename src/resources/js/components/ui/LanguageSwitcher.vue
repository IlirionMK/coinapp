<template>
    <div class="flex gap-2">
        <button
            v-for="lng in locales"
            :key="lng"
            @click="switchLang(lng)"
            :class="[
                'px-3 py-1 rounded text-sm font-semibold transition-colors duration-150',
                locale.value === lng
                    ? 'bg-blue-600 text-white dark:bg-blue-500 dark:text-white'
                    : 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600'
            ]"
        >
            {{ lng.toUpperCase() }}
        </button>
    </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { i18n } from '@/i18n'
import { loadLocaleMessages } from '../../utils/loadLocaleMessages'

const { locale } = useI18n()
const locales = ['en', 'pl']

async function switchLang(lng) {
    if (locale.value !== lng) {
        localStorage.setItem('locale', lng)
        await loadLocaleMessages(i18n, lng)
    }
}
</script>
