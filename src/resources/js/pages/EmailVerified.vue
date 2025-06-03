<template>
    <div class="max-w-md w-full mx-auto mt-20 p-6 sm:p-8 rounded-xl shadow-md bg-white dark:bg-gray-800 text-center">
        <div class="flex justify-center mb-4">
            <CheckCircle class="w-12 h-12 text-green-600 dark:text-green-400" />
        </div>
        <h2 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-2">
            {{ $t('email_verified.title') }}
        </h2>
        <p class="mb-6 text-gray-700 dark:text-gray-300">
            {{ $t('email_verified.message') }}
        </p>
        <RouterLink
            to="/dashboard"
            class="inline-block bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600
                   text-white font-semibold px-4 py-2 rounded transition-colors duration-150 shadow-sm hover:shadow-md"
        >
            {{ $t('profile.back_to_dashboard') }}
        </RouterLink>
    </div>
</template>

<script setup>
import { CheckCircle } from 'lucide-vue-next'
</script>
