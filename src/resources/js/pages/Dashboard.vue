<template>
    <div class="min-h-screen bg-gray-100 py-8 px-4">
        <div class="max-w-4xl mx-auto space-y-6">
            <h1 class="text-2xl font-bold">
                {{ $t('dashboard.title') }}
            </h1>

            <div class="bg-white p-6 rounded-xl shadow">
                <p><strong>{{ $t('dashboard.name') }}:</strong> {{ user.name }}</p>
                <p><strong>{{ $t('dashboard.email') }}:</strong> {{ user.email }}</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-xl font-semibold mb-4">{{ $t('dashboard.subscriptions') }}</h2>
                <ul v-if="subscriptions.length" class="space-y-2">
                    <li v-for="coin in subscriptions" :key="coin.id">
                        {{ coin.name }} ({{ coin.symbol.toUpperCase() }}) — ${{ coin.price }}
                    </li>
                </ul>
                <p v-else class="text-gray-500">{{ $t('dashboard.no_subscriptions') }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/utils/axios'
import useUser from '@/stores/user'

const { user } = useUser()
const subscriptions = ref([])

onMounted(async () => {
    const res = await axios.get('/coin-subscriptions')
    subscriptions.value = res.data
})
</script>
