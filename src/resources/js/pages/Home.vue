<template>
    <section class="space-y-10">
         <div>
            <h2 class="text-2xl font-bold mb-2">{{ t('crypto_dashboard') }}</h2>
            <p class="text-gray-600">{{ t('crypto_dashboard_description') }}</p>
        </div>

         <ConverterPreview />

         <div>
            <h3 class="text-xl font-semibold mb-4">{{ t('crypto_table') }}</h3>
            <CryptoTable :coins="coins" :loading="loading" />
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import CryptoTable from '@/components/CryptoTable.vue'
import ConverterPreview from '@/components/ConverterPreview.vue'

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
