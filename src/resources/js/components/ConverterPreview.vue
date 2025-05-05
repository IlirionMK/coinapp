<template>
    <section class="bg-white p-4 rounded-lg shadow mb-6">
        <h3 class="text-xl font-semibold mb-4">{{ t('converter_title') }}</h3>

        <form @submit.prevent="convert" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">{{ t('from') }}</label>
                <select v-model="from" class="w-full border rounded p-2">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">{{ t('to') }}</label>
                <select v-model="to" class="w-full border rounded p-2">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">{{ t('amount') }}</label>
                <input
                    type="number"
                    v-model="amount"
                    min="0"
                    step="any"
                    class="w-full border rounded p-2"
                />
            </div>

            <div v-if="result !== null" class="text-lg font-medium mt-4">
                {{ t('converted') }}: {{ result }}
            </div>

            <button
                type="submit"
                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                {{ t('convert') }}
            </button>
        </form>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const coins = ref([])
const from = ref('')
const to = ref('')
const amount = ref(1)
const result = ref(null)

onMounted(async () => {
    try {
        const response = await fetch('/api/coins')
        coins.value = await response.json()
        if (coins.value.length >= 2) {
            from.value = coins.value[0].symbol
            to.value = coins.value[1].symbol
        }
    } catch (e) {
        console.error('Failed to load coins:', e)
    }
})

const convert = async () => {
    if (!from.value || !to.value || amount.value <= 0) return
    try {
        const res = await fetch(`/api/convert?from=${from.value}&to=${to.value}&amount=${amount.value}`)
        const data = await res.json()
        result.value = data.converted
    } catch (e) {
        console.error('Conversion failed:', e)
    }
}
</script>
