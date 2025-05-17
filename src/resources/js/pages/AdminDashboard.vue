<template>
    <section class="p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

        <p class="text-gray-600">Welcome, {{ user.name }} ({{ user.email }})</p>

        <div class="mt-6">
            <p class="text-sm text-gray-500">You have administrative privileges.</p>
        </div>

        <RouterLink
            to="/"
            class="inline-block mt-6 text-blue-600 hover:underline text-sm"
        >
            ← {{ $t('nav.home') }}
        </RouterLink>
    </section>
</template>

<script setup>
import useUser from '@/stores/user'
import { RouterLink } from 'vue-router'

const { user } = useUser()
</script>
