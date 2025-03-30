<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">
        <Header />
        <CryptoTable />
        <Converter />
        <NewsFeed />
    </div>
</template>

<script setup>
import Header from '@/components/home/Header.vue'
import CryptoTable from '@/components/home/CryptoTable.vue'
import Converter from '@/components/home/Converter.vue'
import NewsFeed from '@/components/home/NewsFeed.vue'

</script>
