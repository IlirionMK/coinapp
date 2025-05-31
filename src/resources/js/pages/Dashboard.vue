<template>
    <div class="min-h-screen bg-gray-100 py-8 px-4">
        <div class="max-w-4xl mx-auto space-y-6">
            <h1 class="text-2xl font-bold">{{ $t('dashboard.title') }}</h1>

            <div class="flex justify-end">
                <LanguageSwitcher />
            </div>

            <!-- User Info -->
            <div class="bg-white p-6 rounded-xl shadow" v-if="user">
                <p><strong>{{ $t('dashboard.name') }}:</strong> {{ user.name }}</p>
                <p><strong>{{ $t('dashboard.email') }}:</strong> {{ user.email }}</p>

                <RouterLink
                    to="/profile"
                    class="inline-block mt-4 text-blue-600 hover:underline text-sm"
                >
                    {{ $t('dashboard.edit_profile') }}
                </RouterLink>
            </div>
            <div v-else class="bg-white p-6 rounded-xl shadow text-gray-500">
                {{ $t('loading') }}...
            </div>

            <!-- Subscriptions -->
            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-xl font-semibold mb-4">{{ $t('dashboard.subscriptions') }}</h2>

                <div v-if="subscriptions.length">
                    <table class="w-full text-left border border-gray-200">
                        <thead class="bg-gray-100 text-sm font-semibold">
                        <tr>
                            <th class="p-2">{{ $t('dashboard.coin') }}</th>
                            <th class="p-2">{{ $t('dashboard.price') }}</th>
                            <th class="p-2">{{ $t('dashboard.frequency') }}</th>
                            <th class="p-2">{{ $t('dashboard.threshold') }}</th>
                            <th class="p-2">{{ $t('dashboard.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="sub in subscriptions" :key="sub.coin_id" class="border-t">
                            <td class="p-2">
                                {{ sub.coin.name }} ({{ sub.coin.symbol.toUpperCase() }})
                            </td>
                            <td class="p-2">${{ sub.coin.price }}</td>
                            <td class="p-2">
                                <select
                                    v-model="sub.notification_frequency"
                                    class="border rounded px-2 py-1"
                                >
                                    <option value="instant">{{ $t('dashboard.frequency_instant') }}</option>
                                    <option value="daily">{{ $t('dashboard.frequency_daily') }}</option>
                                    <option value="none">{{ $t('dashboard.frequency_none') }}</option>
                                </select>
                            </td>
                            <td class="p-2">
                                <input
                                    v-model.number="sub.change_threshold"
                                    type="number"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    class="border rounded px-2 py-1 w-24"
                                />
                            </td>
                            <td class="p-2 space-x-2">
                                <button
                                    @click="updateSubscription(sub)"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700"
                                >
                                    {{ $t('dashboard.save') }}
                                </button>
                                <button
                                    @click="unsubscribe(sub.coin_id)"
                                    class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700"
                                >
                                    {{ $t('dashboard.unsubscribe') }}
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p v-else class="text-gray-500">{{ $t('dashboard.no_subscriptions') }}</p>
            </div>

            <RouterLink
                to="/"
                class="inline-block text-blue-600 hover:underline text-sm"
            >
                ← {{ $t('nav.home') }}
            </RouterLink>

            <Toast ref="toastRef" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import axios from '@/utils/axios'
import useUser from '@/stores/user'
import Toast from '@/components/ui/Toast.vue'
import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'

const { user } = useUser()
const subscriptions = ref([])
const toastRef = ref()

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
