<template>
    <section class="p-6">
        <h2 class="text-2xl font-bold mb-4">{{ t('crypto_table') }}</h2>

        <CryptoTable :coins="coins" :loading="loading" />
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import CryptoTable from '/resources/js/components/CryptoTable.vue'

const { t } = useI18n()

const coins = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const response = await fetch('/api/coins')
        const data = await response.json()
        coins.value = data
    } catch (error) {
        console.error('Failed to load coins:', error)
    } finally {
        loading.value = false
    }
})
</script>
