<script setup>
import Header from "@/components/layout/Header.vue"
import Navigation from '@/components/layout/Navigation.vue'
import Footer from '@/components/layout/Footer.vue'
</script>

<template>
    <div class="flex flex-col min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white transition-colors">
        <Header />
        <Navigation />
        <main class="flex-1">
            <div class="max-w-screen-xl mx-auto w-full px-4 py-6">
                <RouterView />
            </div>
        </main>
        <Footer />
    </div>
</template>
