import axios from 'axios'

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
})

 api.interceptors.response.use(
    response => response,
    error => Promise.reject(error)
)

export default api
