<template>
    <section class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow duration-300 w-full max-w-md">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-5">{{ t('converter.title') }}</h3>

        <form class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ t('converter.from') }}</label>
                <select v-model="from"
                        class="w-full rounded-xl px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }}
                    </option>
                </select>
            </div>

            <div class="flex justify-center">
                <button type="button" @click="swapCurrencies" class="hover:text-blue-600 transition" title="Swap currencies">
                    <SwapIcon class="w-5 h-5 mt-1" />
                </button>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ t('converter.to') }}</label>
                <select v-model="to"
                        class="w-full rounded-xl px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ t('converter.amount') }}</label>
                <input type="number"
                       v-model.number="amount"
                       min="0"
                       step="any"
                       class="w-full rounded-xl px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition [appearance:textfield]" />
            </div>

            <div v-if="result !== null" class="text-base mt-2 font-semibold text-center text-gray-900 dark:text-white">
                {{ amount }} {{ from.toUpperCase() }} =
                <span class="text-blue-600">{{ result }}</span>
                {{ to.toUpperCase() }}
            </div>
        </form>
    </section>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useCoins } from '@/utils/useCoins'
import { ArrowRightLeft as SwapIcon } from 'lucide-vue-next'

const { t } = useI18n()
const { coins, loadCoins } = useCoins()

const from = ref('')
const to = ref('')
const amount = ref(1)
const result = ref(null)

const swapCurrencies = () => {
    const temp = from.value
    from.value = to.value
    to.value = temp
}

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

<style scoped>
input[type='number']::-webkit-outer-spin-button,
input[type='number']::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type='number'] {
    -moz-appearance: textfield;
}
</style>
