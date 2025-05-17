<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 text-center p-6">
        <h1 class="text-2xl font-bold text-red-600 mb-4">
            Session Expired
        </h1>
        <p class="text-gray-700 mb-6">
            Your session has expired. Please log in again.
        </p>

        <RouterLink
            to="/login"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm"
        >
            Login again
        </RouterLink>

        <RouterLink
            v-if="previousPath"
            :to="previousPath"
            class="mt-4 text-sm text-blue-600 underline hover:no-underline"
        >
            Return to previous page
        </RouterLink>

        <RouterLink
            to="/"
            class="mt-2 text-sm text-gray-500 hover:underline"
        >
            Back to homepage
        </RouterLink>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'

const previousPath = ref(null)

onMounted(() => {
    const path = localStorage.getItem('logoutRedirectPath')
    if (path && path !== '/session-expired' && path !== '/login') {
        previousPath.value = path
    }
})
</script>
