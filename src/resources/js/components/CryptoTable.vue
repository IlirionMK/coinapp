<template>
    <div class="overflow-x-auto">
        <div v-if="loading" class="text-center py-10">
            Loading...
        </div>

        <table v-else class="min-w-full table-auto border-collapse">
            <thead>
            <tr class="bg-gray-100 dark:bg-gray-800">
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
                class="border-t hover:bg-gray-50 dark:hover:bg-gray-700"
            >
                <td class="px-4 py-2 text-center">{{ index + 1 }}</td>
                <td class="px-4 py-2 flex items-center gap-2">
                    <img
                        v-if="coin.icon_path"
                        :src="coin.icon_path"
                        :alt="coin.name"
                        class="rounded-full object-cover"
                        style="width: 24px; height: 24px;"
                        @error="(e) => { e.target.onerror = null;
                        e.target.src = '/icons/default.png'; }"
                    />
                    <span>{{ coin.name }} ({{ coin.symbol.toUpperCase() }})</span>
                </td>
                <td class="px-4 py-2 text-right">
                    {{ coin.price ? `$${Number(coin.price).toLocaleString()}` : '-' }}
                </td>
                <td
                    class="px-4 py-2 text-right"
                    :class="{
                        'text-green-500': coin.price_change_percentage_24h > 0,
                        'text-red-500': coin.price_change_percentage_24h < 0
                    }"
                >
                    {{ coin.price_change_percentage_24h !== null
                    ? `${Number(coin.price_change_percentage_24h).toFixed(2)}%`
                    : '-' }}
                </td>
                <td class="px-4 py-2 text-right">
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
    event.target.src = '/icons/default.png'; // fallback если не загрузилась иконка
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
