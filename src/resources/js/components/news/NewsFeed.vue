<template>
    <div class="max-w-5xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">{{ $t('news.title') }}</h1>

         <div class="flex flex-wrap md:flex-nowrap gap-4 mb-6">
            <div class="flex-1 min-w-[160px]">
                <label class="block mb-1 text-sm font-medium">{{ $t('news.filter_by_coin') }}</label>
                <select v-model="currencyCode" @change="loadPage(1)"
                        class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">{{ $t('news.all_coins') }}</option>
                    <option v-for="code in availableCoins" :key="code" :value="code">
                        {{ code }}
                    </option>
                </select>
            </div>

            <div class="flex-1 min-w-[160px]">
                <label class="block mb-1 text-sm font-medium">{{ $t('news.filter_by_date') }}</label>
                <input type="date" v-model="fromDate" @change="loadPage(1)"
                       class="w-full border border-gray-300 rounded px-3 py-2" />
            </div>

            <div class="flex-1 min-w-[160px]">
                <label class="block mb-1 text-sm font-medium">{{ $t('news.search_keywords') }}</label>
                <input type="text" v-model="searchTerm" @keydown.enter="loadPage(1)"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       placeholder="e.g. SEC, Binance, regulation" />
            </div>

            <div class="flex items-end">
                <button @click="resetFilters"
                        class="text-sm text-blue-600 hover:underline">
                    {{ $t('news.clear_filters') }}
                </button>
            </div>
        </div>

        <div v-if="loading" class="text-center text-gray-500">Loading...</div>

        <div v-else>
            <div v-if="news.length === 0" class="text-center text-gray-500">No news found.</div>

            <ul class="space-y-4">
                <li v-for="item in news" :key="item.uuid" class="bg-white dark:bg-gray-800 shadow rounded p-4">
                    <h2 class="text-lg font-semibold mb-1">{{ item.title }}</h2>
                    <p class="text-sm text-gray-600 mb-2">
                        {{ formatDate(item.published_at) }} — {{ item.source || 'Unknown' }}
                    </p>

                    <p class="text-gray-700 text-sm mb-3" v-if="item.summary">
                        {{ item.summary }}
                    </p>

                    <div class="flex flex-wrap gap-2 mb-3">
            <span v-for="currency in item.currencies ?? []" :key="currency.code"
                  class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
              {{ currency.code.toUpperCase() }}
            </span>
                    </div>

                    <a :href="item.url" target="_blank" class="text-blue-600 hover:underline text-sm">
                        {{ $t('news.read_more') }}
                    </a>
                </li>
            </ul>

            <div
                class="mt-6 flex justify-center"
                v-if="pagination && pagination.total > pagination.per_page"
            >
                <button class="mx-1 px-3 py-1 border rounded"
                        :disabled="pagination.current_page === 1"
                        @click="loadPage(pagination.current_page - 1)">
                    {{ $t('pagination.prev') }}
                </button>
                <button class="mx-1 px-3 py-1 border rounded"
                        :disabled="pagination.current_page === pagination.last_page"
                        @click="loadPage(pagination.current_page + 1)">
                    {{ $t('pagination.next') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()

const news = ref([])
const pagination = ref({})
const loading = ref(true)

const fromDate = ref('')
const searchTerm = ref('')
const currencyCode = ref('')
const availableCoins = ref([])

const loadPage = async (page = 1) => {
    loading.value = true
    try {
        const response = await axios.get('/api/news', {
            params: {
                page,
                from: fromDate.value || undefined,
                search: searchTerm.value || undefined,
                currency: currencyCode.value || undefined,
            },
        })

        news.value = response.data.data
        pagination.value = response.data.meta || {}

        const uniqueCodes = new Set()
        for (const item of response.data.data) {
            for (const currency of item.currencies ?? []) {
                uniqueCodes.add(currency.code.toUpperCase())
            }
        }
        availableCoins.value = Array.from(uniqueCodes).sort()
    } catch (error) {
        console.error('Failed to load news', error)
    } finally {
        loading.value = false
    }
}

const resetFilters = () => {
    fromDate.value = ''
    searchTerm.value = ''
    currencyCode.value = ''
    loadPage(1)
}

const formatDate = (isoString) => {
    return new Date(isoString).toLocaleString()
}

onMounted(() => {
    const fromQuery = route.query.currency
    if (fromQuery) {
        currencyCode.value = fromQuery.toUpperCase()
    }
    loadPage()
})
</script>
