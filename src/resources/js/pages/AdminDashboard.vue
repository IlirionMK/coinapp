<template>
    <section class="p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

        <p class="text-gray-600">Welcome, {{ user.name }} ({{ user.email }})</p>

        <div class="mt-6">
            <p class="text-sm text-gray-500">You have administrative privileges.</p>

        </div>
    </section>
</template>

<script setup>
import useUser from '@/stores/user'

const { user } = useUser()
</script>
