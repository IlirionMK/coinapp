<template>
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="flex justify-end mb-2">
            <button @click="resetFilters" class="text-sm text-blue-600 hover:underline whitespace-nowrap">
                {{ $t('news.clear_filters') }}
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-700 mb-1">
                    {{ $t('news.filter_by_coin') }}
                </label>
                <select v-model="currencyCode" @change="loadPage(1)"
                        class="w-full h-10 text-sm px-3 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-400 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ $t('news.all_coins') }}</option>
                    <option v-for="code in availableCoins" :key="code" :value="code">
                        {{ code }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-700 mb-1">
                    {{ $t('news.filter_by_date') }}
                </label>
                <input type="date" v-model="fromDate" @change="loadPage(1)"
                       class="w-full h-10 text-sm px-3 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-400 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-700 mb-1">
                    {{ $t('news.search_keywords') }}
                </label>
                <input type="text" v-model="searchTerm" @keydown.enter="loadPage(1)"
                       :placeholder="$t('news.search_placeholder')"
                       class="w-full h-10 text-sm px-3 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-400 dark:border-gray-600 rounded-md placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
        </div>

        <div v-if="loading" class="text-center text-gray-500 dark:text-gray-400">Loading...</div>

        <div v-else>
            <div v-if="news.length === 0" class="text-center text-gray-500 dark:text-gray-400">No news found.</div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-6">
                <div
                    v-for="item in news"
                    :key="item.uuid"
                    class="flex flex-col justify-between h-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm p-5 transition-transform duration-200 hover:shadow-md hover:-translate-y-1"
                >
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white leading-snug mb-2">
                            {{ item.title }}
                        </h2>

                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-4 mb-3" v-if="item.summary">
                            {{ item.summary }}
                        </p>

                        <div class="flex flex-wrap gap-2 mb-3">
                            <span
                                v-for="currency in item.currencies ?? []"
                                :key="currency.code"
                                class="bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 text-[11px] font-medium px-2 py-0.5 rounded-full"
                            >
                                {{ currency.code.toUpperCase() }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 border-t pt-3 border-gray-100 dark:border-gray-700">
                            <span class="italic truncate">{{ item.source || 'Unknown' }}</span>
                            <span class="ml-auto whitespace-nowrap">{{ formatDate(item.published_at) }}</span>
                        </div>

                        <div class="mt-2 text-right">
                            <a
                                :href="item.url"
                                target="_blank"
                                class="text-sm font-medium text-blue-600 hover:underline"
                            >
                                {{ $t('news.read_more') }} →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-center" v-if="pagination && pagination.total > pagination.per_page">
                <button class="mx-1 px-3 py-1 border rounded text-sm"
                        :disabled="pagination.current_page === 1"
                        @click="loadPage(pagination.current_page - 1)">
                    {{ $t('pagination.prev') }}
                </button>
                <button class="mx-1 px-3 py-1 border rounded text-sm"
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
    const date = new Date(isoString)
    return date.toLocaleDateString() + ', ' + date.toLocaleTimeString()
}

onMounted(() => {
    const fromQuery = route.query.currency
    if (fromQuery) {
        currencyCode.value = fromQuery.toUpperCase()
    }
    loadPage()
})
</script>
