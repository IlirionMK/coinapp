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
        type: String,
        required: true
    }
})

const subscribedIds = ref([])

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
    if (!user.value) return

    try {
        if (isSubscribed.value) {
            await axios.delete(`/coin-subscriptions/${props.coinId}`)
            subscribedIds.value = subscribedIds.value.filter(id => id !== props.coinId)
            toast?.value?.show(t('unsubscribed_success'))
        } else {
            await axios.post('/coin-subscriptions', { coin_id: props.coinId })
            subscribedIds.value.push(props.coinId)
            toast?.value?.show(t('subscribed_success'))
        }
    } catch (err) {
        console.error('Failed to toggle subscription', err)
        toast?.value?.show(t('subscription_error'))
    }
}

onMounted(() => {
    if (user.value && subscribedIds.value.length === 0) {
        fetchSubscriptions()
    }
})
</script>
