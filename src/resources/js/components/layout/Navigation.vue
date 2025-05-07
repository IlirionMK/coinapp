<template>
    <nav class="bg-white border-b shadow-sm py-4">
        <div class="max-w-screen-xl mx-auto px-4 flex justify-between items-center gap-4">
            <div class="flex items-center gap-8">
                <RouterLink to="/" class="text-2xl font-bold text-gray-800 hover:text-blue-600">
                    CoinApp
                </RouterLink>
                <RouterLink to="/" class="text-gray-700 hover:text-blue-600 text-base">
                    {{ t('home') }}
                </RouterLink>
                <RouterLink to="/convert" class="text-gray-700 hover:text-blue-600 text-base">
                    {{ t('converter') }}
                </RouterLink>
            </div>

            <div class="flex items-center gap-4">
                <RouterLink to="/login" class="text-gray-600 hover:text-blue-600 text-sm">
                    {{ t('login') }}
                </RouterLink>
                <RouterLink to="/register" class="text-white bg-blue-600 hover:bg-blue-700 text-sm px-4 py-2 rounded">
                    {{ t('register') }}
                </RouterLink>

                <LanguageSwitcher />
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'
import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'

const { t } = useI18n()
</script>
