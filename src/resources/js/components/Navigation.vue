<template>
    <header class="bg-white border-b shadow-sm">
        <div class="container mx-auto flex items-center justify-between px-4 py-3">
            <!-- Logo -->
            <router-link to="/" class="text-xl font-bold text-gray-800 hover:text-gray-600">
                CoinApp
            </router-link>

            <!-- Right side -->
            <div class="flex items-center space-x-4">
                <router-link
                    to="/"
                    class="text-sm font-medium text-gray-700 hover:text-gray-900"
                >
                    {{ t('nav.home') }}
                </router-link>
                <router-link
                    to="/convert"
                    class="text-sm font-medium text-gray-700 hover:text-gray-900"
                >
                    {{ t('nav.converter') }}
                </router-link>

                <!-- Language switch -->
                <LanguageSwitcher />
            </div>
        </div>
    </header>
</template>

<script setup>
import LanguageSwitcher from './ui/LanguageSwitcher.vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>
