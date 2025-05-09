import { ref } from 'vue'
import axios from '@/utils/axios'

const coins = ref([])
const loading = ref(false)
const error = ref(null)
let loaded = false

export function useCoins() {
    const loadCoins = async () => {
        if (loaded || loading.value) return
        loading.value = true
        try {
            const response = await axios.get('/coins')
            coins.value = response.data
            loaded = true
        } catch (e) {
            console.error('Failed to load coins:', e)
            error.value = e
        } finally {
            loading.value = false
        }
    }

    return {
        coins,
        loading,
        error,
        loadCoins,
    }
}
