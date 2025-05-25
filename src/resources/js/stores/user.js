import { ref } from 'vue'
import api from '@/utils/axios'
import rawAxios from 'axios'

const user = ref(null)
const error = ref(null)
let isLoggingOut = false
let fetching = null
let csrfReady = false

const ensureCsrf = async () => {
    if (csrfReady) return
    await rawAxios.get('/sanctum/csrf-cookie', {
        withCredentials: true
    })
    csrfReady = true
}

const fetchUser = async () => {
    if (user.value) return true

    if (!fetching) {
        fetching = api.get('/user')
            .then(({ data }) => {
                user.value = data
                return true
            })
            .catch((err) => {
                if (err.response?.status === 401) {
                    user.value = null
                    return false
                }
                throw err
            })
            .finally(() => {
                fetching = null
            })
    }

    return fetching
}

const login = async (form, router) => {
    error.value = null
    try {
        await ensureCsrf()
        await api.post('/login', form)

        const redirectTo = localStorage.getItem('logoutRedirectPath') || '/dashboard'
        localStorage.removeItem('logoutRedirectPath')

        router.push(redirectTo)
    } catch (err) {
        handleError(err)
    }
}

const register = async (form, router) => {
    error.value = null
    try {
        await ensureCsrf()
        await api.post('/register', form)
        router.push('/verify-email')
    } catch (err) {
        handleError(err)
    }
}

const logout = async (router) => {
    if (isLoggingOut) return
    isLoggingOut = true

    try {
        await api.post('/logout')
    } catch (e) {
        if (e.response?.status !== 401) {
            console.error('Logout failed:', e)
        }
    }

    user.value = null
    await router.push('/session-expired')

    isLoggingOut = false
}

const handleError = (err) => {
    if (err.response?.status === 422) {
        const errors = err.response.data.errors
        error.value = Object.values(errors).flat()[0]
    } else {
        error.value = err.response?.data?.message || 'Authentication error'
    }
}

export default function useUser() {
    return {
        user,
        error,
        login,
        register,
        logout,
        fetchUser,
    }
}
