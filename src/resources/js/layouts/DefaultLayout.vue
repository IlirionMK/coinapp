<template>
    <div class="flex flex-col min-h-screen bg-gray-50 text-gray-900">
        <!-- <Header /> -->

        <Navigation />

        <main class="flex-1">
            <div class="max-w-screen-xl mx-auto w-full px-4 py-6">
                <RouterView />
            </div>
        </main>

        <Footer />
    </div>
</template>

<script setup>
import Navigation from '@/components/layout/Navigation.vue'
import Footer from '@/components/layout/Footer.vue'
 </script>
