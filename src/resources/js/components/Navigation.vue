<template>
    <nav class="flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-800 shadow">
        <!-- Логотип / Название -->
        <div class="text-xl font-semibold">
            <router-link to="/" class="text-blue-600 dark:text-white hover:underline">
                CryptoDash
            </router-link>
        </div>

        <!-- Навигационные ссылки -->
        <div class="space-x-6 text-sm font-medium hidden md:flex">
            <router-link
                v-for="link in links"
                :key="link.to"
                :to="link.to"
                class="text-gray-700 dark:text-gray-200 hover:underline"
                active-class="text-blue-600 dark:text-blue-400 underline"
            >
                {{ t(link.label) }}
            </router-link>
        </div>

        <!-- Переключатель языка -->
        <div>
            <button
                @click="toggleLang"
                class="bg-gray-700 text-white px-4 py-1 rounded text-xs hover:bg-gray-600"
            >
                {{ locale.value === 'en' ? 'PL' : 'EN' }}
            </button>
        </div>
    </nav>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import { loadLocaleMessages } from '@/utils/loadLocaleMessages'
import i18n from './i18n'

const { t, locale } = useI18n()

const links = [
    { to: '/', label: 'home' },
    { to: '/news', label: 'news' },
    { to: '/convert', label: 'convert' },
    { to: '/about', label: 'about' }
]

async function toggleLang() {
    const newLocale = locale.value === 'en' ? 'pl' : 'en'
    localStorage.setItem('locale', newLocale)
    await loadLocaleMessages(i18n, newLocale)
}
</script>
