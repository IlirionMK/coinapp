<template>
    <button
        @click="toggle"
        :disabled="isLoading"
        class="flex items-center justify-center w-8 h-8 rounded-full transition hover:bg-gray-100"
        :title="isSubscribed ? t('unsubscribe') : t('subscribe')"
    >
        <svg
            v-if="isSubscribed"
            xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 text-yellow-500"
            fill="currentColor"
            viewBox="0 0 24 24"
        >
            <path d="M12 2a7 7 0 0 0-7 7v3.586l-1.707 1.707A1 1 0 0 0 4 16h16a1 1 0 0 0 .707-1.707L19 12.586V9a7 7 0 0 0-7-7zm0 20a3 3 0 0 0 2.995-2.824L15 19h-6a3 3 0 0 0 2.824 2.995L12 22z"/>
        </svg>
        <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 text-gray-400"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
    </button>
</template>

<script setup>
import { ref, onMounted, computed, inject } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from '@/utils/axios'
import useUser from '@/stores/user'

const { t } = useI18n()
const { user } = useUser()
const toast = inject('toast')

const props = defineProps({
    coinId: {
        type: Number, // 👈 используем Number, так как coin.id — число
        required: true
    }
})

const subscribedIds = ref([])
const isLoading = ref(false)
let loaded = false

const isSubscribed = computed(() => {
    return subscribedIds.value.includes(props.coinId)
})

const fetchSubscriptions = async () => {
    try {
        const res = await axios.get('/coin-subscriptions')
        subscribedIds.value = res.data.map(sub => sub.coin_id)
    } catch (err) {
        console.error('Failed to fetch subscriptions', err)
    }
}

const toggle = async () => {
    if (!user.value || isLoading.value) return
    isLoading.value = true

    try {
        if (isSubscribed.value) {
            await axios.delete(`/coin-subscriptions/${props.coinId}`)
            subscribedIds.value = subscribedIds.value.filter(id => id !== props.coinId)
            toast?.value?.show(t('unsubscribed_success'), 'success')
        } else {
            await axios.post('/coin-subscriptions', { coin_id: props.coinId })
            subscribedIds.value.push(props.coinId)
            toast?.value?.show(t('subscribed_success'), 'success')
        }
    } catch (err) {
        console.error('Failed to toggle subscription', err)
        toast?.value?.show(err.response?.data?.message || t('subscription_error'), 'error')
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    if (!user.value || loaded) return
    loaded = true
    fetchSubscriptions()
})
</script>
