<template>
    <div class="w-full">
        <header class="bg-gray-900 border-b shadow-sm py-4 w-full">
            <div class="max-w-screen-xl mx-auto px-4">
                <RouterLink to="/" class="inline-block">
                    <Logo />
                </RouterLink>
            </div>
        </header>
    </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
import Logo from '@/components/ui/Logo.vue'
</script>
