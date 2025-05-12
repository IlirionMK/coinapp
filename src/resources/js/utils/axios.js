import axios from 'axios'

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
})

api.interceptors.response.use(
    response => response,
    error => {
        if (import.meta.env.DEV && error.response?.status === 401) {
            console.warn('[401 Unauthorized] — guest user or expired session')
        }
        return Promise.reject(error)
    }
)

export default api
