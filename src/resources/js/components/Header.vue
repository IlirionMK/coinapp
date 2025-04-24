<template>
    <header class="flex justify-between items-center border-b pb-4">
        <h1 class="text-3xl font-bold">{{ t('welcome') }}</h1>
        <LanguageSwitcher />
    </header>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'

const { t } = useI18n()
</script>
