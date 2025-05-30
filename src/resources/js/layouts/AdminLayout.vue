<script setup>
import { useRouter } from 'vue-router'
import useUser from '@/stores/user'

const { logout } = useUser()
const router = useRouter()
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <header class="bg-white border-b px-6 py-4 shadow-sm flex justify-between items-center">
            <h1 class="text-xl font-bold text-red-600">Admin Panel</h1>
            <button @click="logout(router)" class="text-sm text-blue-600 hover:underline">Logout</button>
        </header>

        <main class="flex-1 px-6 py-6">
            <router-view />
        </main>

        <footer class="text-center text-xs py-4 text-gray-400">
            &copy; {{ new Date().getFullYear() }} CoinApp Admin
        </footer>
    </div>
</template>
