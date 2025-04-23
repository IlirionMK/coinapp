<template>
    <div class="min-h-screen flex flex-col bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white">
        <!-- Навбар -->
        <header class="border-b shadow bg-white dark:bg-gray-800 sticky top-0 z-10">
            <Navigation />
        </header>

        <!-- Контент -->
        <main class="flex-1 container mx-auto px-4 py-8">
            <router-view />
        </main>

        <!-- Футер -->
        <footer class="border-t text-center text-sm py-4 text-gray-500 dark:text-gray-400">
            © {{ new Date().getFullYear() }} Crypto Dashboard
        </footer>
    </div>
</template>

<script setup>
import Navigation from '../components/Navigation.vue'
</script>
