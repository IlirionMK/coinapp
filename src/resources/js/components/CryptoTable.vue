<template>
    <div class="max-w-full overflow-x-auto rounded-lg shadow-sm bg-white p-4">
        <div class="mb-4 flex items-center gap-2">
            <input
                v-model="search"
                type="text"
                :placeholder="t('search_placeholder')"
                class="border rounded p-2 w-full max-w-md text-sm"
            />
        </div>

        <table class="w-full table-auto border-collapse text-sm">
            <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left cursor-pointer" @click="sortBy('name')">
                    {{ t('name') }}
                    <span v-if="sort.key === 'name'">({{ sort.direction === 'asc' ? '↑' : '↓' }})</span>
                </th>
                <th class="px-4 py-2 text-right cursor-pointer" @click="sortBy('price')">
                    {{ t('price') }}
                    <span v-if="sort.key === 'price'">({{ sort.direction === 'asc' ? '↑' : '↓' }})</span>
                </th>
                <th class="px-4 py-2 text-right cursor-pointer" @click="sortBy('price_change_percentage_24h')">
                    {{ t('24h_change') }}
                    <span v-if="sort.key === 'price_change_percentage_24h'">
                            ({{ sort.direction === 'asc' ? '↑' : '↓' }})
                        </span>
                </th>
                <th class="px-4 py-2 text-right cursor-pointer" @click="sortBy('market_cap')">
                    {{ t('market_cap') }}
                    <span v-if="sort.key === 'market_cap'">({{ sort.direction === 'asc' ? '↑' : '↓' }})</span>
                </th>
                <th class="px-4 py-2 text-center">
                    {{ t('actions') }}
                </th>
            </tr>
            </thead>

            <tbody>
            <tr
                v-for="(coin, index) in paginatedCoins"
                :key="coin.id"
                class="border-t hover:bg-gray-50"
            >
                <td class="px-4 py-2 text-center text-gray-500">
                    {{ index + 1 + (currentPage - 1) * itemsPerPage }}
                </td>
                <td class="px-4 py-2 flex items-center gap-2 font-medium">
                    <img
                        :src="coin.icon_path"
                        :alt="coin.name"
                        class="w-6 h-6 rounded-full object-cover"
                        loading="lazy"
                        @error="handleIconError"
                    />
                    <span>{{ coin.name }} ({{ coin.symbol?.toUpperCase?.() ?? '-' }})</span>
                </td>
                <td class="px-4 py-2 text-right">
                    {{ formatPrice(coin.price) }}
                </td>
                <td
                    class="px-4 py-2 text-right font-semibold"
                    :class="{
                            'text-green-500': coin.price_change_percentage_24h > 0,
                            'text-red-500': coin.price_change_percentage_24h < 0
                        }"
                >
                    {{ formatPercent(coin.price_change_percentage_24h) }}
                </td>
                <td class="px-4 py-2 text-right">
                    {{ formatPrice(coin.market_cap) }}
                </td>
                <td class="px-4 py-2 text-center">
                    <button
                        @click="subscribe(coin)"
                        :disabled="subscribing[coin.id]"
                        class="text-xs px-2 py-1 rounded bg-blue-100 hover:bg-blue-200 text-blue-600 disabled:opacity-50"
                    >
                        {{ t('subscribe') }}
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="flex justify-between items-center px-4 py-3 text-sm text-gray-600">
            <div>
                {{ t('showing') }}
                {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredCoins.length) }}
                {{ t('of') }} {{ filteredCoins.length }}
            </div>
            <div class="flex items-center gap-2">
                <button
                    class="px-2 py-1 border rounded disabled:opacity-50"
                    @click="currentPage--"
                    :disabled="currentPage === 1"
                >
                    {{ t('prev') }}
                </button>
                <button
                    v-for="page in totalPages"
                    :key="page"
                    @click="currentPage = page"
                    class="px-3 py-1 border rounded"
                    :class="{ 'bg-blue-500 text-white': page === currentPage }"
                >
                    {{ page }}
                </button>
                <button
                    class="px-2 py-1 border rounded disabled:opacity-50"
                    @click="currentPage++"
                    :disabled="currentPage === totalPages"
                >
                    {{ t('next') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from '@/utils/axios'
import useUser from '@/stores/user'

const { t } = useI18n()
const { user } = useUser()

const props = defineProps({
    coins: {
        type: Array,
        required: true,
    },
    loading: {
        type: Boolean,
        default: false,
    },
})

const search = ref('')
const itemsPerPage = 25
const currentPage = ref(1)

const sort = ref({
    key: 'market_cap',
    direction: 'desc',
})

const sortBy = (key) => {
    if (sort.value.key === key) {
        sort.value.direction = sort.value.direction === 'asc' ? 'desc' : 'asc'
    } else {
        sort.value.key = key
        sort.value.direction = 'asc'
    }
}

const filteredCoins = computed(() => {
    const query = search.value.trim().toLowerCase()
    if (!query) return props.coins
    return props.coins.filter(
        (coin) =>
            coin.name.toLowerCase().includes(query) ||
            coin.symbol.toLowerCase().includes(query)
    )
})

const sortedCoins = computed(() => {
    return [...filteredCoins.value].sort((a, b) => {
        const modifier = sort.value.direction === 'asc' ? 1 : -1
        const aVal = a[sort.value.key] ?? 0
        const bVal = b[sort.value.key] ?? 0
        return (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) * modifier
    })
})

const paginatedCoins = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return sortedCoins.value.slice(start, start + itemsPerPage)
})

const totalPages = computed(() =>
    Math.ceil(filteredCoins.value.length / itemsPerPage)
)

const handleIconError = (e) => {
    e.target.src = '/icons/default.png'
}

function formatPrice(value) {
    return typeof value === 'number'
        ? `$${value.toLocaleString(undefined, { maximumFractionDigits: 2 })}`
        : '-'
}

function formatPercent(value) {
    return typeof value === 'number'
        ? `${value.toFixed(2)}%`
        : '-'
}

const subscribing = ref({})

const subscribe = async (coin) => {
    if (!user.value) {
        alert('You must be logged in to subscribe.')
        return
    }

    if (subscribing.value[coin.id]) return

    subscribing.value[coin.id] = true

    try {
        await axios.post('/coin-subscriptions', {
            coin_id: coin.id,
        })
        alert(`Subscribed to ${coin.name}`)
    } catch (err) {
        console.error(err)
        alert('Subscription failed.')
    } finally {
        subscribing.value[coin.id] = false
    }
}
</script>
