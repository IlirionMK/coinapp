<template>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
        <tr>
          <th class="px-6 py-3">{{ t('name') }}</th>
          <th class="px-6 py-3">{{ t('price') }}</th>
          <th class="px-6 py-3">{{ t('change') }}</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        <tr v-for="coin in coins" :key="coin.id" class="hover:bg-gray-50">
          <td class="px-6 py-4 font-medium text-gray-800">
            <div class="flex items-center gap-2">
                <img
                    :src="`/proxy/image?url=${encodeURIComponent(coin.image)}`"
                    alt=""
                    class="h-6 w-6 object-contain"
                    width="24"
                    height="24"
                    loading="lazy"
                />

                {{ coin.name }} ({{ coin.symbol.toUpperCase() }})
            </div>
          </td>
          <td class="px-6 py-4 text-gray-700">
            {{ formatCurrency(Number(coin.price)) }}
          </td>
          <td class="px-6 py-4">
            <span
              v-if="!isNaN(Number(coin.price_change_percentage_24h))"
              :class="{
                'text-green-600': Number(coin.price_change_percentage_24h) >= 0,
                'text-red-600': Number(coin.price_change_percentage_24h) < 0
              }"
            >
              {{ Number(coin.price_change_percentage_24h).toFixed(2) }}%
            </span>
            <span v-else class="text-gray-500">–</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const coins = ref([])

onMounted(async () => {
  const res = await fetch('/api/coins')
  coins.value = await res.json()
})

function formatCurrency(value) {
  if (typeof value !== 'number' || isNaN(value)) return '–'
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(value)
}
</script>
