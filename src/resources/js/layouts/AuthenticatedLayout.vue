<script setup>
import { onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import useUser from '@/stores/user'

const { user, logout } = useUser()
const router = useRouter()

 watch(user, (val) => {
    if (val === null) {
        router.push('/login')
    }
})

onMounted(() => {
    if (user.value === null) {
        router.push('/login')
    }
})
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
