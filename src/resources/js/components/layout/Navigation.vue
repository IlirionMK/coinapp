<template>
    <nav class="sticky top-0 z-40 bg-white dark:bg-gray-900 border-b shadow-sm">
        <div class="w-full">
            <div class="max-w-screen-xl mx-auto flex flex-wrap justify-center md:justify-between items-center gap-4 px-4 py-4 text-center">
                <div class="hidden md:flex items-center gap-6">
                    <RouterLink to="/" class="nav-link">
                        {{ t('nav.home') }}
                    </RouterLink>
                    <RouterLink to="/about" class="nav-link">
                        {{ t('nav.about') }}
                    </RouterLink>
                    <RouterLink to="/news" class="nav-link">
                        {{ t('nav.news') }}
                    </RouterLink>
                    <RouterLink to="/convert" class="nav-link">
                        {{ t('nav.converter') }}
                    </RouterLink>
                </div>

                <div class="flex items-center gap-2 flex-wrap justify-center md:justify-end w-full md:w-auto">
                    <div class="hidden md:block mr-2">
                        <Dropdown>
                            <template #trigger="{ toggle }">
                                <button @click="toggle" class="dropdown-button">
                                    {{ t('nav.converter') }}
                                </button>
                            </template>
                            <ConverterPreview />
                        </Dropdown>
                    </div>

                    <template v-if="user">
                        <Dropdown>
                            <template #trigger="{ toggle }">
                                <button @click="toggle" class="user-button">
                                    <UserCircle class="w-4 h-4" />
                                    <span>{{ user.name ?? 'User' }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </template>
                            <div class="py-1 text-sm text-left">
                                <RouterLink to="/profile" class="dropdown-link text-blue-600 dark:text-blue-400">
                                    {{ t('nav.profile') }}
                                </RouterLink>
                                <RouterLink :to="user.role === 'admin' ? '/admin' : '/dashboard'" class="dropdown-link text-blue-600 dark:text-blue-400">
                                    {{ t('nav.dashboard') }}
                                </RouterLink>
                                <button @click="handleLogout" class="dropdown-link text-red-600">
                                    {{ t('nav.logout') }}
                                </button>
                            </div>
                        </Dropdown>
                    </template>

                    <template v-else>
                        <RouterLink to="/login" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 text-sm">
                            {{ t('nav.login') }}
                        </RouterLink>
                        <RouterLink to="/register" class="btn-primary">
                            {{ t('nav.register') }}
                        </RouterLink>
                    </template>

                    <ThemeToggle />
                    <LanguageSwitcher />

                    <button class="md:hidden ml-2" @click="toggleMobileMenu" aria-label="Toggle menu">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <transition name="fade">
        <div
            v-if="mobileMenu"
            class="md:hidden bg-white dark:bg-gray-900 px-4 pb-6 space-y-4 shadow-md"
        >
            <div class="space-y-2 pt-4">
                <RouterLink to="/" class="nav-link block">{{ t('nav.home') }}</RouterLink>
                <RouterLink to="/about" class="nav-link block">{{ t('nav.about') }}</RouterLink>
                <RouterLink to="/news" class="nav-link block">{{ t('nav.news') }}</RouterLink>
                <RouterLink to="/convert" class="nav-link block">{{ t('nav.converter') }}</RouterLink>
            </div>

            <div class="border-t pt-4">
                <ConverterPreview />
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'
import ConverterPreview from '@/components/ConverterPreview.vue'
import Dropdown from '@/components/ui/Dropdown.vue'
import ThemeToggle from '@/components/ui/ThemeToggle.vue'
import useUser from '@/stores/user'
import { UserCircle } from 'lucide-vue-next'

const { t } = useI18n()
const router = useRouter()
const mobileMenu = ref(false)
const { user, logout } = useUser()

const handleLogout = () => {
    logout(router)
}
const toggleMobileMenu = () => {
    mobileMenu.value = !mobileMenu.value
}
</script>

<style scoped>
.nav-link {
    @apply text-gray-700 dark:text-gray-300 hover:text-blue-600 text-base;
}
.dropdown-button {
    @apply bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-blue-600 hover:text-white px-3 py-1 rounded text-sm transition;
}
.user-button {
    @apply flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 hover:text-blue-600;
}
.dropdown-link {
    @apply block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800;
}
.btn-primary {
    @apply text-white bg-blue-600 hover:bg-blue-700 text-sm px-4 py-2 rounded transition;
}
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
