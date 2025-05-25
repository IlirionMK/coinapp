<script setup>
import useUser from '@/stores/user'
import { useRouter } from 'vue-router'

const { logout } = useUser()
const router = useRouter()
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-50 text-gray-900">
        <header class="bg-white border-b shadow-sm">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold">CoinApp (Auth)</h1>
                <button @click="logout(router)" class="text-sm text-blue-600 hover:underline">Logout</button>
            </div>
        </header>

        <main class="flex-1 container mx-auto px-4 py-6">
            <slot />
        </main>

        <footer class="text-center text-sm py-4 text-gray-500">
            &copy; {{ new Date().getFullYear() }} CoinApp
        </footer>
    </div>
</template>
