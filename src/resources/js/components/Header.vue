<template>
    <header class="flex justify-between items-center border-b pb-4">
        <h1 class="text-3xl font-bold">{{ t('welcome') }}</h1>

        <button @click="toggleLang" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
            {{ locale.value === 'en' ? 'PL' : 'EN' }}
        </button>
    </header>
</template>

<script setup>
import {useI18n} from 'vue-i18n'
import i18n from '@/components/i18n'
import {loadLocaleMessages} from '@/utils/loadLocaleMessages'

const {t, locale} = useI18n()

async function toggleLang() {
    const newLocale = locale.value === 'en' ? 'pl' : 'en'
    localStorage.setItem('locale', newLocale)
    await loadLocaleMessages(i18n, newLocale)
}
</script>
