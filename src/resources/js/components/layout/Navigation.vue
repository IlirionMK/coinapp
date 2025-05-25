<template>
    <nav class="bg-white dark:bg-gray-900 border-b shadow-sm py-4">
        <div class="max-w-screen-xl mx-auto px-4 flex justify-between items-center gap-4">
            <RouterLink to="/" class="text-xl font-bold text-gray-800 dark:text-white">
                CoinApp
            </RouterLink>

            <div class="hidden md:flex items-center gap-6">
                <RouterLink to="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 text-base">
                    {{ t('nav.home') }}
                </RouterLink>
                <RouterLink to="/about" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 text-base">
                    {{ t('nav.about') }}
                </RouterLink>
                <RouterLink to="/news" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 text-base">
                    {{ t('nav.news') }}
                </RouterLink>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:block mr-2">
                    <Dropdown>
                        <template #trigger="{ toggle }">
                            <button
                                @click="toggle"
                                class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-blue-600 hover:text-white px-3 py-1 rounded text-sm transition"
                            >
                                {{ t('nav.converter') }}
                            </button>
                        </template>
                        <ConverterPreview />
                    </Dropdown>
                </div>

                <template v-if="user">
                    <Dropdown>
                        <template #trigger="{ toggle }">
                            <button
                                @click="toggle"
                                class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 hover:text-blue-600"
                            >
                                👤 {{ user.name ?? 'User' }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </template>

                        <div class="py-1 text-sm text-left">
                            <RouterLink
                                to="/profile"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800"
                            >
                                {{ t('nav.profile') }}
                            </RouterLink>
                            <RouterLink
                                :to="user.role === 'admin' ? '/admin' : '/dashboard'"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800"
                            >
                                {{ t('nav.dashboard') }}
                            </RouterLink>
                            <button
                                @click="handleLogout"
                                class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-800"
                            >
                                {{ t('nav.logout') }}
                            </button>
                        </div>
                    </Dropdown>
                </template>

                <template v-else>
                    <RouterLink to="/login" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 text-sm">
                        {{ t('nav.login') }}
                    </RouterLink>
                    <RouterLink
                        to="/register"
                        class="text-white bg-blue-600 hover:bg-blue-700 text-sm px-4 py-2 rounded"
                    >
                        {{ t('nav.register') }}
                    </RouterLink>
                </template>

                <LanguageSwitcher />

                <div class="block md:hidden">
                    <button @click="mobileMenu = !mobileMenu">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="mobileMenu" class="md:hidden px-4 mt-2 space-y-2">
            <RouterLink to="/" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600">
                {{ t('nav.home') }}
            </RouterLink>
            <RouterLink to="/about" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600">
                {{ t('nav.about') }}
            </RouterLink>
            <RouterLink to="/news" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600">
                {{ t('nav.news') }}
            </RouterLink>

            <div class="pt-2 border-t">
                <ConverterPreview />
            </div>

            <div class="pt-4 border-t">
                <template v-if="user">
                    <div class="text-sm text-gray-700 dark:text-gray-200 mb-2">
                        👤 {{ user.name ?? 'User' }}
                    </div>
                    <RouterLink
                        to="/profile"
                        class="block text-sm text-blue-600 hover:underline mb-2"
                    >
                        {{ t('nav.profile') }}
                    </RouterLink>
                    <RouterLink
                        :to="user.role === 'admin' ? '/admin' : '/dashboard'"
                        class="block text-sm text-blue-600 hover:underline mb-2"
                    >
                        {{ t('nav.dashboard') }}
                    </RouterLink>
                    <button
                        @click="handleLogout"
                        class="text-sm text-red-600 hover:underline"
                    >
                        {{ t('nav.logout') }}
                    </button>
                </template>
                <template v-else>
                    <RouterLink to="/login" class="block text-gray-600 dark:text-gray-300 hover:text-blue-600 text-sm">
                        {{ t('nav.login') }}
                    </RouterLink>
                    <RouterLink
                        to="/register"
                        class="block text-white bg-blue-600 hover:bg-blue-700 text-sm px-4 py-2 rounded mt-2"
                    >
                        {{ t('nav.register') }}
                    </RouterLink>
                </template>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'

import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'
import ConverterPreview from '@/components/ConverterPreview.vue'
import Dropdown from '@/components/ui/Dropdown.vue'
import useUser from '@/stores/user'

const { t } = useI18n()
const router = useRouter()
const mobileMenu = ref(false)

const { user, logout } = useUser()

const handleLogout = () => {
    logout(router)
}
</script>
