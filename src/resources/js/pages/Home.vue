<template>
    <section>
        <h1 class="text-3xl font-bold mb-6">{{ t('top_cryptocurrencies') }}</h1>

        <div v-if="loading" class="flex justify-center items-center h-32">
            <svg class="animate-spin w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
            </svg>
        </div>
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
