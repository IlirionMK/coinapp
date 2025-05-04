<template>
    <div class="overflow-x-auto rounded-lg shadow-sm bg-white">
        <div v-if="loading" class="flex justify-center items-center py-12 text-gray-500">
            <svg class="animate-spin h-6 w-6 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
            </svg>
            {{ t('loading') }}
        </div>

        <table v-else class="min-w-full table-auto border-collapse">
            <thead>
            <tr class="bg-gray-100 text-gray-700 text-sm">
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">{{ t('name') }}</th>
                <th class="px-4 py-2 text-right">{{ t('price') }}</th>
                <th class="px-4 py-2 text-right">{{ t('24h_change') }}</th>
                <th class="px-4 py-2 text-right">{{ t('market_cap') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(coin, index) in coins"
                :key="coin.id"
                class="border-t hover:bg-gray-50 text-sm"
            >
                <td class="px-4 py-3 text-center text-gray-500">{{ index + 1 }}</td>
                <td class="px-4 py-3 flex items-center gap-2 font-medium">
                    <img
                        v-if="coin.icon_path"
                        :src="coin.icon_path"
                        :alt="coin.name"
                        class="w-6 h-6 rounded-full object-cover"
                        @error="handleIconError"
                    />
                    <span>{{ coin.name }} ({{ coin.symbol.toUpperCase() }})</span>
                </td>
                <td class="px-4 py-3 text-right">
                    {{ coin.price ? `$${Number(coin.price).toLocaleString()}` : '-' }}
                </td>
                <td
                    class="px-4 py-3 text-right font-semibold"
                    :class="{
                        'text-green-500': coin.price_change_percentage_24h > 0,
                        'text-red-500': coin.price_change_percentage_24h < 0,
                        'text-gray-500': coin.price_change_percentage_24h === 0
                    }"
                >
                    {{ coin.price_change_percentage_24h !== null
                    ? `${Number(coin.price_change_percentage_24h).toFixed(2)}%`
                    : '-' }}
                </td>
                <td class="px-4 py-3 text-right">
                    {{ coin.market_cap ? `$${Number(coin.market_cap).toLocaleString()}` : '-' }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const coins = ref([]);
const loading = ref(true);

const handleIconError = (event) => {
    event.target.src = '/icons/default.png';
};

onMounted(async () => {
    try {
        const response = await fetch('/api/coins');
        const data = await response.json();
        coins.value = data;
    } catch (error) {
        console.error('Failed to load coins:', error);
    } finally {
        loading.value = false;
    }
});
</script>
