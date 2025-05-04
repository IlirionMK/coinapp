<template>
    <nav class="flex items-center justify-between px-6 py-4 bg-white shadow">
        <!-- Логотип / Название -->
        <div class="text-xl font-semibold">
            <router-link to="/" class="text-blue-600 hover:underline">
                CryptoDash
            </router-link>
        </div>

        <!-- Навигационные ссылки -->
        <div class="space-x-6 text-sm font-medium hidden md:flex">
            <router-link
                v-for="link in links"
                :key="link.to"
                :to="link.to"
                class="text-gray-700 hover:underline"
                active-class="text-blue-600 underline"
            >
                {{ t(link.label) }}
            </router-link>
        </div>

        <!-- Переключатель языка -->
        <div>
            <LanguageSwitcher />
        </div>
    </nav>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import LanguageSwitcher from '@/components/LanguageSwitcher.vue'

const { t } = useI18n()

const links = [
    { to: '/', label: 'home' },
    { to: '/news', label: 'news' },
    { to: '/convert', label: 'convert' },
    { to: '/about', label: 'about' }
]
</script>
