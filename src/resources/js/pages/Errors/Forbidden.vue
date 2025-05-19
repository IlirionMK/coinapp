<template>
    <div class="flex flex-col items-center justify-center h-screen text-center">
        <h1 class="text-6xl font-bold text-red-600 mb-4">403</h1>
        <p class="text-xl text-gray-700 mb-6">Access Denied — You are not authorized to view this page.</p>
        <RouterLink
            to="/"
            class="text-blue-600 hover:underline text-sm"
        >
            ← Back to Home
        </RouterLink>
    </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
