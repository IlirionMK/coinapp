<template>
    <nav class="bg-gray-800 text-white px-6 py-4 flex gap-6 text-lg">
        <router-link
            to="/"
            class="hover:underline"
            :class="{ 'text-yellow-400 font-semibold': $route.name === 'home' }"
        >
            Главная
        </router-link>
        <router-link
            to="/convert"
            class="hover:underline"
            :class="{ 'text-yellow-400 font-semibold': $route.name === 'convert' }"
        >
            Конвертер
        </router-link>
        <router-link
            to="/about"
            class="hover:underline"
            :class="{ 'text-yellow-400 font-semibold': $route.name === 'about' }"
        >
            О проекте
        </router-link>
    </nav>
</template>

<script setup>
</script>
