<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Валюта {{ id }}</h1>
        <p>Здесь будут подробности о криптовалюте с ID: <strong>{{ id }}</strong></p>
    </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
const { params } = useRoute()
const id = params.id
</script>
