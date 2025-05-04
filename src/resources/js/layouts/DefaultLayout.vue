<template>
    <div class="min-h-screen flex flex-col bg-gray-50 text-gray-900">
        <Navigation />

        <main class="flex-1 container mx-auto px-4 py-6">
            <slot />
        </main>

        <Footer />
    </div>
</template>

<script setup>
import Navigation from '@/components/Navigation.vue'
import Footer from '@/components/layout/Footer.vue'
</script>
