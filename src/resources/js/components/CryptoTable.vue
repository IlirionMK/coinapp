<template>
    <div class="w-full overflow-x-auto rounded-lg shadow-sm bg-white dark:bg-gray-900 p-4 sm:p-6">
        <div class="mb-4 flex items-center gap-2">
            <input
                v-model="search"
                type="text"
                :placeholder="t('search_placeholder')"
                class="border rounded p-2 w-full max-w-md text-sm bg-white dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100"
            />
        </div>

        <table class="w-full table-auto border-collapse text-sm">
            <thead>
            <tr class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                <th class="px-2 py-2 text-center">#</th>
                <th class="px-2 py-2 text-left cursor-pointer" @click="sortBy('name')">
                    {{ t('name') }}
                    <span v-if="sort.key === 'name'">({{ sort.direction === 'asc' ? '↑' : '↓' }})</span>
                </th>
                <th class="px-2 py-2 text-center cursor-pointer" @click="sortBy('price')">
                    {{ t('price') }}
                    <span v-if="sort.key === 'price'">({{ sort.direction === 'asc' ? '↑' : '↓' }})</span>
                </th>
                <th class="px-2 py-2 text-center cursor-pointer" @click="sortBy('price_change_percentage_24h')">
                    {{ t('24h_change') }}
                    <span v-if="sort.key === 'price_change_percentage_24h'">
              ({{ sort.direction === 'asc' ? '↑' : '↓' }})
            </span>
                </th>
                <th class="px-2 py-2 text-center hidden sm:table-cell cursor-pointer" @click="sortBy('market_cap')">
                    {{ t('market_cap') }}
                    <span v-if="sort.key === 'market_cap'">({{ sort.direction === 'asc' ? '↑' : '↓' }})</span>
                </th>
                <th v-if="canShowActions" class="px-2 py-2 text-center">
                    <span class="hidden sm:inline">{{ t('actions') }}</span>
                    <span class="sm:hidden">🔔</span>
                </th>
            </tr>
            </thead>

            <tbody>
            <tr
                v-for="(coin, index) in paginatedCoins"
                :key="coin.id"
                class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition"
            >
                <td class="px-2 py-2 text-center text-gray-500 dark:text-gray-400">
                    {{ index + 1 + (currentPage - 1) * itemsPerPage }}
                </td>
                <td class="px-2 py-2 flex items-center gap-2 font-medium text-left text-gray-800 dark:text-gray-100 whitespace-nowrap">
                    <img
                        :src="coin.icon_path"
                        :alt="coin.name"
                        class="w-6 h-6 rounded-full object-cover"
                        loading="lazy"
                        @error="handleIconError"
                    />
                    <span
                        class="text-blue-600 hover:underline dark:text-blue-400 cursor-pointer truncate"
                        @click="goToNews(coin.symbol)"
                    >
              <span class="hidden sm:inline">
                {{ coin.name }} ({{ coin.symbol?.toUpperCase?.() ?? '-' }})
              </span>
              <span class="sm:hidden">
                {{ coin.symbol?.toUpperCase?.() ?? '-' }}
              </span>
            </span>
                </td>
                <td class="px-2 py-2 text-center text-gray-900 dark:text-gray-100">
                    {{ formatPrice(coin.price) }}
                </td>
                <td
                    class="px-2 py-2 text-center font-semibold"
                    :class="{
              'text-green-500': coin.price_change_percentage_24h > 0,
              'text-red-500': coin.price_change_percentage_24h < 0
            }"
                >
                    {{ formatPercent(coin.price_change_percentage_24h) }}
                </td>
                <td class="px-2 py-2 text-center text-gray-900 dark:text-gray-100 hidden sm:table-cell">
                    {{ formatPrice(coin.market_cap) }}
                </td>
                <td v-if="canShowActions" class="px-2 py-2 text-center">
                    <SubscribeBell
                        :coin-id="coin.id"
                        :subscribed="subscribedIds.includes(coin.id)"
                        @toggle="updateSubscription"
                    />
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Пагинация -->
        <div class="flex flex-col sm:flex-row sm:justify-between items-center gap-4 px-4 py-3 text-sm text-gray-600 dark:text-gray-300 text-center sm:text-left">
            <div class="order-2 sm:order-none">
                {{ t('showing') }}
                {{ (currentPage - 1) * itemsPerPage + 1 }}–{{ Math.min(currentPage * itemsPerPage, filteredCoins.length) }}
                {{ t('of') }} {{ filteredCoins.length }}
            </div>
            <div class="flex items-center justify-center gap-2 order-1 sm:order-none">
                <button
                    class="px-2 py-1 border rounded disabled:opacity-50 dark:border-gray-600"
                    @click="currentPage--"
                    :disabled="currentPage === 1"
                >
                    {{ t('prev') }}
                </button>
                <button
                    class="px-2 py-1 border rounded disabled:opacity-50 dark:border-gray-600"
                    @click="currentPage++"
                    :disabled="currentPage === totalPages"
                >
                    {{ t('next') }}
                </button>
            </div>
            <div class="flex flex-wrap justify-center gap-2 order-3 sm:order-none">
                <button
                    v-for="page in totalPages"
                    :key="page"
                    @click="currentPage = page"
                    class="px-3 py-1 border rounded dark:border-gray-600"
                    :class="{ 'bg-blue-500 text-white dark:bg-blue-600': page === currentPage }"
                >
                    {{ page }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'
import useUser from '@/stores/user'
import axios from '@/utils/axios'
import SubscribeBell from '@/components/ui/SubscribeBell.vue'

const { user } = useUser()
const canShowActions = computed(() => user.value !== null)

const router = useRouter()
const { t } = useI18n()

const props = defineProps({
    coins: Array,
    loading: Boolean,
})

const subscribedIds = ref([])

const fetchSubscriptions = async () => {
    if (!user.value) return
    try {
        const res = await axios.get('/coin-subscriptions')
        subscribedIds.value = res.data.map(sub => sub.coin_id)
    } catch (e) {
        console.error('Subscription fetch failed', e)
    }
}

const updateSubscription = (coinId, subscribed) => {
    if (subscribed) {
        subscribedIds.value.push(coinId)
    } else {
        subscribedIds.value = subscribedIds.value.filter(id => id !== coinId)
    }
}

onMounted(() => {
    fetchSubscriptions()
})

const search = ref('')
const itemsPerPage = 25
const currentPage = ref(1)

const sort = ref({ key: 'market_cap', direction: 'desc' })

const sortBy = (key) => {
    sort.value.key === key
        ? sort.value.direction = sort.value.direction === 'asc' ? 'desc' : 'asc'
        : sort.value = { key, direction: 'asc' }
}

const filteredCoins = computed(() => {
    const query = search.value.trim().toLowerCase()
    return !query
        ? props.coins
        : props.coins.filter(c =>
            c.name.toLowerCase().includes(query) ||
            c.symbol.toLowerCase().includes(query)
        )
})

const sortedCoins = computed(() => {
    const modifier = sort.value.direction === 'asc' ? 1 : -1
    return [...filteredCoins.value].sort((a, b) => {
        const aVal = a[sort.value.key] ?? 0
        const bVal = b[sort.value.key] ?? 0
        return (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) * modifier
    })
})

const paginatedCoins = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return sortedCoins.value.slice(start, start + itemsPerPage)
})

const totalPages = computed(() => Math.ceil(filteredCoins.value.length / itemsPerPage))

const handleIconError = (e) => {
    e.target.src = '/icons/default.png'
}

function formatPrice(value) {
    if (typeof value !== 'number') return '-'
    const options = value >= 1
        ? { minimumFractionDigits: 2, maximumFractionDigits: 2 }
        : { minimumFractionDigits: 2, maximumFractionDigits: 8 }
    return `$${value.toLocaleString(undefined, options)}`
}

function formatPercent(value) {
    return typeof value === 'number' ? `${value.toFixed(2)}%` : '-'
}

function goToNews(symbol) {
    if (symbol) router.push(`/news?currency=${symbol.toUpperCase()}`)
}
</script>
