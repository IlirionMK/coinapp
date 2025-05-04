<template>
    <div class="bg-white shadow rounded-xl p-6 w-full max-w-3xl mx-auto">
        <h2 class="text-xl font-bold mb-4">{{ t('converter') }}</h2>
        <p class="text-gray-600 mb-6">{{ t('converter_intro') }}</p>

        <form class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <!-- From -->
            <div>
                <label class="block text-sm font-medium mb-1">{{ t('converter.from') }}</label>
                <select v-model="from" class="w-full border rounded px-3 py-2">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }} ({{ coin.symbol.toUpperCase() }})
                    </option>
                </select>
            </div>

            <!-- To -->
            <div>
                <label class="block text-sm font-medium mb-1">{{ t('converter.to') }}</label>
                <select v-model="to" class="w-full border rounded px-3 py-2">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }} ({{ coin.symbol.toUpperCase() }})
                    </option>
                </select>
            </div>

            <!-- Amount -->
            <div>
                <label class="block text-sm font-medium mb-1">{{ t('converter.amount') }}</label>
                <input
                    type="number"
                    v-model.number="amount"
                    class="w-full border rounded px-3 py-2"
                    min="0"
                    step="any"
                />
            </div>
        </form>

        <!-- Result -->
        <div class="mt-4 text-sm text-gray-700" v-if="converted !== null">
            {{ amount }} {{ from.toUpperCase() }} ≈ {{ converted }} {{ to.toUpperCase() }}
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const coins = ref([])
const from = ref('btc')
const to = ref('eth')
const amount = ref(1)
const converted = ref(null)

onMounted(async () => {
    const response = await fetch('/api/coins')
    const data = await response.json()
    coins.value = data
})

watch([from, to, amount], async () => {
    if (from.value === to.value || amount.value <= 0) {
        converted.value = amount.value
        return
    }

    try {
        const res = await fetch(`/api/convert?from=${from.value}&to=${to.value}&amount=${amount.value}`)
        const data = await res.json()
        converted.value = data.converted
    } catch (err) {
        console.error(err)
        converted.value = null
    }
})
</script>
