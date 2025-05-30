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
    await rawAxios.get('/sanctum/csrf-cookie', { withCredentials: true })
    csrfReady = true
}

const fetchUser = async () => {
    if (user.value) return true

    await ensureCsrf() // ← ДОБАВЛЕНО

    if (!fetching) {
        fetching = api.get('/user')
            .then(({ data }) => { user.value = data; return true })
            .catch(err => {
                if (err.response?.status === 401) {
                    user.value = null
                    return false
                }
                throw err
            })
            .finally(() => { fetching = null })
    }
    return fetching
}

const login = async (form) => {
    error.value = null
    await ensureCsrf()
    return api.post('/login', form, { withCredentials: true })
}

const register = async (form) => {
    error.value = null
    await ensureCsrf()
    return api.post('/register', form, { withCredentials: true })
}

const logout = async (router) => {
    if (isLoggingOut) return
    isLoggingOut = true

    try {
        await api.post('/logout')
    } catch (e) {
        if (e.response?.status !== 401) console.error('Logout failed', e)
    }

    user.value = null
    await router.push('/session-expired')
    isLoggingOut = false
    csrfReady = false
}

export default function useUser () {
    return { user, error, login, register, logout, fetchUser }
}
