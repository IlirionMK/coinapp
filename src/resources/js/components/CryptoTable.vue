<template>
    <table class="w-full table-auto border-collapse bg-white shadow rounded overflow-hidden">
        <thead class="bg-gray-100 text-left text-sm text-gray-600">
        <tr>
            <th class="px-4 py-2">#</th>
            <th class="px-4 py-2">{{ t('coin') }}</th>
            <th class="px-4 py-2">{{ t('price') }}</th>
            <th class="px-4 py-2">24h</th>
            <th class="px-4 py-2">{{ t('market_cap') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="loading">
            <td colspan="5" class="text-center text-gray-500 py-10">
                {{ t('loading') }}...
            </td>
        </tr>
        <tr
            v-else
            v-for="(coin, index) in coins"
            :key="coin.id"
            class="border-t hover:bg-gray-50 dark:hover:bg-gray-800 transition"
        >
            <td class="px-4 py-2">{{ index + 1 }}</td>
            <td class="px-4 py-2 flex items-center gap-2">
                <img
                    loading="lazy"
                    :src="`/proxy/image?url=${encodeURIComponent(coin.image)}`"
                    :alt="coin.name"
                    class="w-6 h-6"
                />
                {{ coin.name }} ({{ coin.symbol.toUpperCase() }})
            </td>
            <td class="px-4 py-2">{{ formatCurrency(coin.price) }}</td>
            <td
                class="px-4 py-2 font-semibold"
                :class="coin.price_change_percentage_24h > 0 ? 'text-green-600' : 'text-red-600'"
            >
                {{ coin.price_change_percentage_24h.toFixed(2) }}%
            </td>
            <td class="px-4 py-2">${{ coin.market_cap.toLocaleString() }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

// Деструктурируем props сразу — IDE не будет ругаться
const { coins, loading } = defineProps({
    coins: { type: Array, required: true },
    loading: { type: Boolean, default: false }
})

const { t } = useI18n()

function formatCurrency(value) {
    if (typeof value !== 'number' || isNaN(value)) return '-'
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(value)
}
</script>
