<template>
    <nav class="p-4 flex justify-between items-center">
        <div class="space-x-4">
            <router-link to="/" class="hover:underline text-blue-500">{{ t('home') }}</router-link>
            <router-link to="/convert" class="hover:underline text-blue-500">{{ t('convert') }}</router-link>
            <router-link to="/about" class="hover:underline text-blue-500">{{ t('about') }}</router-link>
        </div>

        <button @click="toggleLang" class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 text-white">
            {{ locale.value === 'en' ? 'PL' : 'EN' }}
        </button>
    </nav>
</template>

<script setup>
import { onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import i18n from './i18n'
import { loadLocaleMessages } from '../utils/loadLocaleMessages'

const { t, locale } = useI18n()

onMounted(async () => {
    const savedLocale = localStorage.getItem('locale')
    if (savedLocale && savedLocale !== locale.value) {
        await loadLocaleMessages(i18n, savedLocale)
    }
})

async function toggleLang() {
    const newLocale = locale.value === 'en' ? 'pl' : 'en'
    localStorage.setItem('locale', newLocale)
    await loadLocaleMessages(i18n, newLocale)
}
</script>
