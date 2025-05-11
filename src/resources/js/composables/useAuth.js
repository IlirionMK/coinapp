import { ref } from 'vue'
import axios from '@/utils/axios'
import { useRouter } from 'vue-router'

const user = ref(null)
const error = ref(null)

export default function useAuth() {
    const router = useRouter()

    const getUser = async () => {
        try {
            const res = await axios.get('/user')
            user.value = res.data
        } catch (err) {
            user.value = null
        }
    }

    const register = async (form) => {
        error.value = null
        try {
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/register', form)
            await getUser()
            router.push('/')
        } catch (err) {
            handleError(err)
        }
    }

    const login = async (form) => {
        error.value = null
        try {
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/login', form)
            await getUser()
            router.push('/')
        } catch (err) {
            handleError(err)
        }
    }

    const logout = async () => {
        await axios.post('/logout')
        user.value = null
        router.push('/login')
    }

    const handleError = (err) => {
        if (err.response?.status === 422) {
            const errors = err.response.data.errors
            error.value = Object.values(errors).flat()[0]
        } else {
            error.value = err.response?.data?.message || 'Authentication error'
        }
    }

    return {
        user,
        error,
        login,
        register,
        logout,
        getUser,
    }
}
