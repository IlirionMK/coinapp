<template>
    <div class="flex flex-col min-h-screen bg-gray-50 text-gray-900">
        <div class="w-full max-w-screen-xl mx-auto px-4 py-6 flex-1 flex flex-col gap-6">
            <Header />
            <Navigation />
            <main class="flex-1">
                <RouterView />
            </main>
            <Footer />
        </div>
    </div>
</template>

<script setup>
import Header from '@/components/layout/Header.vue'
import Navigation from '@/components/layout/Navigation.vue'
import Footer from '@/components/layout/Footer.vue'
</script>
