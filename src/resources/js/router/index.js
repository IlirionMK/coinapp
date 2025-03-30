import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/components/pages/home.vue'), // ленивый импорт
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
