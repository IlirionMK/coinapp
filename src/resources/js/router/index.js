import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../pages/Home.vue'),
    },
    {
        path: '/about',
        name: 'about',
        component: () => import('../pages/About.vue'),
    },
    {
        path: '/convert',
        name: 'convert',
        component: () => import('../pages/Convert.vue'),
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
