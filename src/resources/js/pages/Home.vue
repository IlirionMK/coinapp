<template>
    <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div v-if="loading" class="flex justify-center items-center h-32">
            <svg class="animate-spin w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                />
            </svg>
        </div>

        <div v-else-if="error" class="text-red-600 dark:text-red-400 text-center">
            {{ error }}
        </div>

        <div v-else>
            <CryptoTable :coins="coins" :loading="loading" />
        </div>
    </section>
</template>

<script setup>
import { onMounted } from 'vue'
import { useCoins } from '@/utils/useCoins'
import CryptoTable from '@/components/CryptoTable.vue'

const { coins, loading, error, loadCoins } = useCoins()

onMounted(() => {
    loadCoins()
})
</script>
