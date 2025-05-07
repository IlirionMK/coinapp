<template>
    <section>
        <h1 class="text-3xl font-bold mb-6">{{ t('top_cryptocurrencies') }}</h1>

        <div v-if="loading" class="text-gray-500">Loading...</div>
        <div v-else-if="error" class="text-red-600">{{ error }}</div>
        <div v-else>
            <CryptoTable :coins="coins" :loading="loading" />
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from '@/utils/axios'
import CryptoTable from '@/components/CryptoTable.vue'

const { t } = useI18n()

const coins = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
    console.time('fetchCoins')
    try {
        const response = await axios.get('/coins')
        coins.value = response.data
    } catch (e) {
        error.value = 'Failed to load coins'
        console.error(e)
    } finally {
        loading.value = false
        console.timeEnd('fetchCoins')
    }
})
console.time('Home mounted')
console.timeEnd('Home mounted')
</script>
