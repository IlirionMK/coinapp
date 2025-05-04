<template>
    <div class="flex flex-col min-h-screen bg-gray-50 text-gray-900">
        <Header />

        <main class="flex-1">
            <div class="max-w-screen-xl mx-auto px-4 py-6">
                <RouterView />
            </div>
        </main>

        <Footer />
    </div>
</template>

<script setup>
import Header from '@/components/layout/Header.vue'
import Footer from '@/components/layout/Footer.vue'
</script>
