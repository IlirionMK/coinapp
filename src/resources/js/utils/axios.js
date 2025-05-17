import axios from 'axios'
import router from '@/router'
import useUser from '@/stores/user'

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
})

api.interceptors.response.use(
    response => response,
    async error => {
        if (error.response?.status === 401) {
            const { logout } = useUser()

             const path = window.location.pathname
            localStorage.setItem('logoutRedirectPath', path)

            await logout(router)
            router.push('/session-expired')
        }

        return Promise.reject(error)
    }
)

export default api
