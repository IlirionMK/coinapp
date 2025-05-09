<template>
    <section class="bg-white p-4 rounded-lg shadow mb-6 w-full max-w-md">
        <h3 class="text-xl font-semibold mb-4">{{ t('converter.title') }}</h3>

        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">{{ t('converter.from') }}</label>
                <select v-model="from" class="w-full border rounded p-2">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">{{ t('converter.to') }}</label>
                <select v-model="to" class="w-full border rounded p-2">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">{{ t('converter.amount') }}</label>
                <input
                    type="number"
                    v-model.number="amount"
                    min="0"
                    step="any"
                    class="w-full border rounded p-2"
                />
            </div>

            <div v-if="result !== null" class="text-base mt-4 font-medium text-gray-800">
                {{ amount }} {{ from.toUpperCase() }} = <span class="text-blue-600">{{ result }}</span> {{ to.toUpperCase() }}
            </div>
        </form>
    </section>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useCoins } from '@/utils/useCoins'

const { t } = useI18n()
const { coins, loadCoins } = useCoins()

const from = ref('')
const to = ref('')
const amount = ref(1)
const result = ref(null)

onMounted(async () => {
    await loadCoins()
    if (coins.value.length >= 2) {
        from.value = coins.value[0].symbol
        to.value = coins.value[1].symbol
    }
})

 watch([from, to, amount], async ([f, t, a]) => {
    if (!f || !t || a <= 0) {
        result.value = null
        return
    }

    try {
        const res = await fetch(`/api/convert?from=${f}&to=${t}&amount=${a}`)
        const data = await res.json()
        result.value = data.converted?.toFixed(6) ?? null
    } catch (e) {
        console.error('Conversion failed:', e)
        result.value = null
    }
})
</script>
