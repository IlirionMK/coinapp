<template>
    <header class="bg-gray-900 shadow-sm py-4 w-full">
        <div class="max-w-screen-xl mx-auto px-4 flex items-center gap-2">
            <RouterLink to="/" class="inline-flex items-center gap-2 text-white text-xl font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" class="h-10 w-10">
                    <circle cx="128" cy="128" r="120" fill="none" stroke="white" stroke-width="12"/>

                    <polygon points="128,45 84,71 84,101 128,128 172,101 172,71" fill="#DAA520"/>

                    <polygon points="128,211 172,185 172,155 128,128 84,155 84,185" fill="#40E0D0"/>

                    <polygon points="128,97 100,113 100,143 128,159 156,143 156,113" fill="#FFFFFF"/>
                </svg>
                CoinApp
            </RouterLink>
        </div>
    </header>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
