<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">
        <CryptoTable />
        <Converter />
        <NewsFeed />
    </div>
</template>

<script setup>
import CryptoTable from '../components/CryptoTable.vue'
import Converter from '../components/Converter.vue'
import NewsFeed from '../components/NewsFeed.vue'
</script>
