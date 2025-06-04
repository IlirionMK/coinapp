<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-4 px-2 sm:px-4 transition-colors">
        <div class="max-w-4xl mx-auto space-y-6">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white text-center sm:text-left">
                {{ $t('dashboard.title') }}
            </h1>

            <div
                v-if="user"
                class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow text-center sm:text-left space-y-1"
            >
                <p class="text-gray-800 dark:text-gray-100 text-sm">
                    <strong>{{ $t('dashboard.name') }}:</strong> {{ user.name }}
                </p>
                <p class="text-gray-800 dark:text-gray-100 text-sm">
                    <strong>{{ $t('dashboard.email') }}:</strong> {{ user.email }}
                </p>
                <RouterLink
                    to="/profile"
                    class="inline-block text-blue-600 dark:text-blue-400 hover:underline text-sm mt-1"
                >
                    {{ $t('dashboard.edit_profile') }}
                </RouterLink>
            </div>

            <div
                v-else
                class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow text-center sm:text-left space-y-1 text-gray-500 dark:text-gray-300"
            >
                <p>{{ $t('dashboard.user_info_not_available') || 'User information not available. Please log in.' }}</p>
                <RouterLink
                    to="/login"
                    class="inline-block text-blue-600 dark:text-blue-400 hover:underline text-sm mt-1"
                >
                    {{ $t('dashboard.login_now') || 'Login now' }}
                </RouterLink>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl shadow transition-colors">
                <h2 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">
                    {{ $t('dashboard.subscriptions') }}
                </h2>

                <div v-if="subscriptions.length">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border border-gray-200 dark:border-gray-700 table-fixed">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium text-center">
                            <tr>
                                <th @click="sortBy('symbol')" class="px-1 py-1 w-14 cursor-pointer">
                                    <span class="sm:inline hidden">{{ $t('dashboard.coin') }}</span>
                                    <span class="sm:hidden">#</span>
                                </th>
                                <th @click="sortBy('price')" class="px-1 py-1 w-16 cursor-pointer">
                                    {{ $t('dashboard.price') }}
                                </th>
                                <th @click="sortBy('frequency')" class="px-1 py-1 w-14 cursor-pointer">
                                    <span class="sm:inline hidden">{{ $t('dashboard.frequency') }}</span>
                                    <span class="sm:hidden"><AlarmClock class="w-4 h-4 inline" /></span>
                                </th>
                                <th @click="sortBy('threshold')" class="px-1 py-1 w-14 cursor-pointer">
                                    <span class="sm:inline hidden">{{ $t('dashboard.threshold') }} (%)</span>
                                    <span class="sm:hidden"><Percent class="w-4 h-4 inline" /></span>
                                </th>
                                <th class="px-1 py-1 w-16">
                                    {{ $t('dashboard.actions') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="sub in sortedSubscriptions"
                                :key="sub.coin_id"
                                class="border-t border-gray-200 dark:border-gray-700 text-center"
                            >
                                <td class="px-1 py-1 truncate">
                                    <span class="sm:inline hidden">{{ sub.coin.name }} ({{ sub.coin.symbol.toUpperCase() }})</span>
                                    <span class="sm:hidden">{{ sub.coin.symbol.toUpperCase() }}</span>
                                </td>
                                <td class="px-1 py-1">${{ sub.coin.price }}</td>
                                <td class="px-1 py-1">
                                    <select
                                        v-model="sub.notification_frequency"
                                        class="border rounded px-1 pr-5 py-1 w-full sm:w-auto bg-white dark:bg-gray-700
                                               border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm"
                                    >
                                        <option value="instant">{{ $t('dashboard.frequency_instant') }}</option>
                                        <option value="daily">{{ $t('dashboard.frequency_daily') }}</option>
                                        <option value="none">{{ $t('dashboard.frequency_none') }}</option>
                                    </select>
                                </td>
                                <td class="px-1 py-1">
                                    <input
                                        v-model.number="sub.change_threshold"
                                        type="number"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        class="border rounded px-2 py-1 w-16 bg-white dark:bg-gray-700
                                               border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm"
                                    />
                                </td>
                                <td class="px-1 py-1 h-[36px]">
                                    <div class="flex items-center justify-center gap-1 h-full">
                                        <button
                                            @click="updateSubscription(sub)"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                                            title="Save"
                                        >
                                            <Check class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="unsubscribe(sub.coin_id)"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200"
                                            title="Unsubscribe"
                                        >
                                            <X class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <p v-else class="text-gray-500 dark:text-gray-300 mt-2">{{ $t('dashboard.no_subscriptions') }}</p>
            </div>

            <Toast ref="toastRef" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import axios from '@/utils/axios'
import useUser from '@/stores/user'
import Toast from '@/components/ui/Toast.vue'
import { Check, X, AlarmClock, Percent } from 'lucide-vue-next'

const { user } = useUser()
const subscriptions = ref([])
const toastRef = ref()

const sortKey = ref(null)
const sortAsc = ref(true)

const sortedSubscriptions = computed(() => {
    if (!sortKey.value) return subscriptions.value
    return [...subscriptions.value].sort((a, b) => {
        let aValue = null, bValue = null
        switch (sortKey.value) {
            case 'symbol':
                aValue = a.coin?.symbol
                bValue = b.coin?.symbol
                break
            case 'price':
                aValue = a.coin?.price
                bValue = b.coin?.price
                break
            case 'frequency':
                aValue = a.notification_frequency
                bValue = b.notification_frequency
                break
            case 'threshold':
                aValue = a.change_threshold
                bValue = b.change_threshold
                break
        }

        if (aValue === null || aValue === undefined) return sortAsc.value ? -1 : 1
        if (bValue === null || bValue === undefined) return sortAsc.value ? 1 : -1

        return sortAsc.value
            ? aValue > bValue ? 1 : (aValue < bValue ? -1 : 0)
            : aValue < bValue ? 1 : (aValue > bValue ? -1 : 0)
    })
})

function sortBy(key) {
    if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value
    } else {
        sortKey.value = key
        sortAsc.value = true
    }
}

onMounted(async () => {
    try {
        const res = await axios.get('/coin-subscriptions')
        subscriptions.value = res.data
    } catch (e) {
        toastRef.value?.show('Failed to load subscriptions.', 'error')
    }
})

const updateSubscription = async (sub) => {
    try {
        await axios.put(`/coin-subscriptions/${sub.coin_id}`, {
            notification_frequency: sub.notification_frequency,
            change_threshold: sub.change_threshold,
        })
        toastRef.value?.show('Subscription updated.', 'success')
    } catch (e) {
        toastRef.value?.show('Update failed.', 'error')
    }
}

const unsubscribe = async (coinId) => {
    try {
        await axios.delete(`/coin-subscriptions/${coinId}`)
        subscriptions.value = subscriptions.value.filter(sub => sub.coin_id !== coinId)
        toastRef.value?.show('Unsubscribed successfully.', 'success')
    } catch (e) {
        toastRef.value?.show('Unsubscribe failed.', 'error')
    }
}
</script>
