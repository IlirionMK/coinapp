<template>
    <div class="space-y-16">
        <!-- Заголовок -->
        <section class="text-center">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4">
                {{ t('welcome') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-300 text-lg">
                {{ t('intro') }}
            </p>
        </section>

        <!-- Таблица валют -->
        <section>
            <CryptoTable :coins="coins" />
        </section>

        <!-- Конвертер -->
        <section>
            <h2 class="text-2xl font-semibold mb-4">{{ t('converter') }}</h2>
            <p class="text-gray-600 dark:text-gray-300">{{ t('converter_intro') }}</p>
        </section>

        <!-- Новости -->
        <section>
            <h2 class="text-2xl font-semibold mb-4">{{ t('news') }}</h2>
            <p class="text-gray-600 dark:text-gray-300">
                {{ t('news_intro') }}
            </p>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useI18n } from 'vue-i18n'
import CryptoTable from '@/components/CryptoTable.vue'

const { t } = useI18n()

const coins = ref([])

onMounted(async () => {
    try {
        const res = await axios.get('/api/coins')
        coins.value = res.data
    } catch (e) {
        console.error('Ошибка загрузки валют:', e)
    }
})
</script>
