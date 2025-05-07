import { ref } from 'vue'
import axios from '@/utils/axios'

const user = ref(null)

const fetchUser = async () => {
    try {
        const response = await axios.get('/user')
        user.value = response.data
    } catch {
        user.value = null
    }
}

const logout = async () => {
    await axios.post('/logout')
    user.value = null
}

export { user, fetchUser, logout }
