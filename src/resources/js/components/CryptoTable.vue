<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
            <tr>
                <th class="px-6 py-3">Ikona</th>
                <th class="px-6 py-3">Nazwa</th>
                <th class="px-6 py-3">Cena</th>
                <th class="px-6 py-3">Zmiana</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-for="coin in coins" :key="coin.id" class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <img :src="coin.image" alt="" class="h-10 w-10 rounded-full" />
                </td>
                <td class="px-6 py-4 font-medium text-gray-800">
                    {{ coin.name }} ({{ coin.symbol.toUpperCase() }})
                </td>
                <td class="px-6 py-4 text-gray-700">
                    {{ formatCurrency(coin.current_price) }}
                </td>
                <td class="px-6 py-4">
            <span :class="{
              'text-green-600': coin.price_change_percentage_24h >= 0,
              'text-red-600': coin.price_change_percentage_24h < 0
            }">
              {{ coin.price_change_percentage_24h.toFixed(2) }}%
            </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const coins = ref([])

function formatCurrency(value) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(value)
}

onMounted(async () => {
    const res = await fetch('/api/coins')
    coins.value = await res.json()
})
</script>
