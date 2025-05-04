<template>
    <div class="flex flex-col min-h-screen">
        <header class="bg-gray-800 text-white p-4">
            <div class="container mx-auto">
                <h1 class="text-2xl font-bold">Crypto App</h1>
            </div>
        </header>

        <main class="flex-1 container mx-auto p-4">
            <slot />
        </main>

        <footer class="bg-gray-800 text-white p-4 text-center">
            © 2025 Crypto App
        </footer>
    </div>
</template>

<script setup>
</script>
