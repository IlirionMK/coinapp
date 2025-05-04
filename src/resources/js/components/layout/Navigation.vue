<template>
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-6">
            <RouterLink to="/" class="text-2xl font-bold text-gray-900">CoinApp</RouterLink>
            <RouterLink to="/" class="text-sm hover:text-blue-600">{{ t('home') }}</RouterLink>
            <RouterLink to="/converter" class="text-sm hover:text-blue-600">{{ t('converter') }}</RouterLink>
        </div>

        <div class="flex items-center gap-4">
            <LanguageSwitcher />
        </div>
    </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'
import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'

const { t } = useI18n()
</script>
