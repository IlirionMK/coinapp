import axios from 'axios'

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
})


api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            console.warn('Unauthorized: redirecting to login')
        }
        return Promise.reject(error)
    }
)

export default api
