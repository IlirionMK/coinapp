<template>
    <section class="text-center space-y-6">
        <h1 class="text-4xl sm:text-5xl font-bold mb-4">{{ t('welcome') }}</h1>
        <p class="text-gray-600 dark:text-gray-300 text-lg">{{ t('intro') }}</p>

        <CryptoTable :coins="coins" />
    </section>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import {useI18n} from 'vue-i18n'
import CryptoTable from '../components/CryptoTable.vue'

const {t} = useI18n()
const coins = ref([])

onMounted(async () => {
    const res = await fetch('/api/coins')
    coins.value = await res.json()
})
</script>
