<template>
    <section class="space-y-10">
        <div class="md:flex md:gap-6">
            <div class="flex-1">
                <ConverterPreview />
            </div>
            <div class="flex-1">
                <CryptoTable :coins="coins" :loading="loading" />
            </div>
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
