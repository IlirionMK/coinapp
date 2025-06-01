<template>
    <div
        :class="[
            variant === 'dropdown'
                ? 'p-2'
                : 'p-6 bg-white dark:bg-gray-800 rounded-xl shadow space-y-4 w-full'
        ]"
    >
        <h2 class="text-lg font-semibold text-center text-gray-800 dark:text-gray-100">
            {{ t('converter.title') }}
        </h2>

        <div class="space-y-4">
            <div>
                <label class="block text-sm text-center text-gray-700 dark:text-gray-300 mb-1">
                    {{ t('converter.from') }}
                </label>
                <select v-model="from" class="w-full rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-center">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }}
                    </option>
                </select>
            </div>

            <div class="flex justify-center relative top-2">
                <button
                    @click="swap"
                    class="p-2 rounded-full border border-gray-400 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-blue-600 hover:text-white transition"
                    title="Swap currencies"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h13M11 3l6 4-6 4m9 6H7m6 4l-6-4 6-4"/>
                    </svg>
                </button>
            </div>

            <div>
                <label class="block text-sm text-center text-gray-700 dark:text-gray-300 mb-1">
                    {{ t('converter.to') }}
                </label>
                <select v-model="to" class="w-full rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-center">
                    <option v-for="coin in coins" :key="coin.id" :value="coin.symbol">
                        {{ coin.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm text-center text-gray-700 dark:text-gray-300 mb-1">
                    {{ t('converter.amount') }}
                </label>
                <input
                    type="text"
                    v-model="amount"
                    inputmode="decimal"
                    pattern="[0-9]*[.,]?[0-9]*"
                    class="w-full rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 appearance-none text-center"
                />
            </div>

            <div
                v-if="from && to && rate"
                class="mt-2 text-center font-semibold text-blue-600 dark:text-blue-400"
            >
                {{ amount }} {{ from.toUpperCase() }} = {{ converted.toFixed(6) }} {{ to.toUpperCase() }}
            </div>
        </div>

        <div
            v-if="variant === 'page' && updatedAt"
            class="text-sm text-gray-500 dark:text-gray-400 text-center mt-4"
        >
            {{ t('converter.last_updated') }}: {{ dayjs(updatedAt).fromNow() }}
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from 'axios'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(relativeTime)

defineProps({
    variant: {
        type: String,
        default: 'page',
    },
})

const { t } = useI18n()
const coins = ref([])
const from = ref(null)
const to = ref(null)
const amount = ref('1')
const rate = ref(null)
const updatedAt = ref(null)

const converted = computed(() => {
    const value = parseFloat(amount.value.toString().replace(',', '.'))
    if (!rate.value || isNaN(value)) return 0
    return value * rate.value
})

const swap = () => {
    const temp = from.value
    from.value = to.value
    to.value = temp
}

onMounted(async () => {
    const res = await axios.get('/api/coins')
    coins.value = res.data

    if (coins.value.length >= 2) {
        from.value = coins.value[0].symbol
        to.value = coins.value[1].symbol
        updatedAt.value = coins.value[0]?.updated_at ?? null
    }
})

watch([from, to], () => {
    if (!from.value || !to.value || from.value === to.value) {
        rate.value = 1
        return
    }

    const fromPrice = coins.value.find(c => c.symbol === from.value)?.price || 1
    const toPrice = coins.value.find(c => c.symbol === to.value)?.price || 1
    rate.value = fromPrice / toPrice
})
</script>
