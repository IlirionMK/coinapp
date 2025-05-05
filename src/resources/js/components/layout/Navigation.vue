<template>
    <nav class="bg-white border-b shadow-sm py-4">
        <div class="flex justify-between items-center gap-4">
            <div class="flex items-center gap-6">
                <RouterLink to="/" class="text-2xl font-bold text-gray-800 hover:text-blue-600">
                    CoinApp
                </RouterLink>
                <RouterLink to="/" class="text-gray-600 hover:text-blue-600">
                    {{ t('home') }}
                </RouterLink>
                <RouterLink to="/convert" class="text-gray-600 hover:text-blue-600">
                    {{ t('converter') }}
                </RouterLink>
            </div>
            <LanguageSwitcher />
        </div>
    </nav>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'
import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'

const { t } = useI18n()
</script>
