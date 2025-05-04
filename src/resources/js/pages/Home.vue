<template>
    <section class="max-w-screen-xl mx-auto px-4 py-6 space-y-8">
        <!-- Заголовок -->
        <h2 class="text-2xl font-bold">{{ t('crypto_dashboard') }}</h2>

        <!-- Конвертер -->
        <ConverterPreview />

        <!-- Таблица -->
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
