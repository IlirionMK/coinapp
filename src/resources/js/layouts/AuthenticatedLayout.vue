<script setup>
import useUser from '@/stores/user'
import { useRouter } from 'vue-router'
import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'
import ThemeToggle from '@/components/ui/ThemeToggle.vue'
import { LogOut } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'

const { logout } = useUser()
const router = useRouter()
const { t } = useI18n()
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white transition-colors">
        <header class="bg-white dark:bg-gray-800 border-b shadow-sm transition-colors">
            <div class="container mx-auto px-4 py-4 flex flex-col sm:flex-row justify-between items-center gap-3">
                <h1 class="text-xl font-bold">CoinApp</h1>

                <div class="flex items-center gap-3">
                    <ThemeToggle />

                    <div class="flex items-center gap-1">
                        <LanguageSwitcher
                            class="flex gap-1
                                   [&>button]:px-2
                                   [&>button]:py-1
                                   [&>button]:rounded
                                   [&>button]:text-sm
                                   [&>button]:transition
                                   [&>button]:bg-gray-200
                                   dark:[&>button]:bg-gray-700
                                   [&>button]:text-gray-700
                                   dark:[&>button]:text-gray-200
                                   [&>button]:hover:bg-gray-300
                                   dark:[&>button]:hover:bg-gray-600" />
                    </div>

                    <button
                        @click="logout(router)"
                        class="flex items-center gap-1 text-sm text-blue-600 dark:text-blue-400 hover:underline"
                    >
                        <LogOut class="w-4 h-4" />
                        {{ t('auth.logout') }}
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-1 container mx-auto px-4 py-6">
            <slot />
        </main>

        <footer class="text-center text-sm py-4 text-gray-500 dark:text-gray-400">
            &copy; {{ new Date().getFullYear() }} CoinApp
        </footer>
    </div>
</template>
