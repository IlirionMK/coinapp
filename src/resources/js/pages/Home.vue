<template>
    <section class="p-6">
        <h2 class="text-2xl font-bold mb-4">{{ $t('crypto_list_title') }}</h2>
        <CryptoTable />
    </section>
</template>

<script setup>
import CryptoTable from '@/components/crypto/CryptoTable.vue'
</script>
