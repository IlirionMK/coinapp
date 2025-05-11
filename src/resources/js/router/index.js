import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Convert from '../pages/Convert.vue'
import About from '../pages/About.vue'
import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Dashboard from '../pages/Dashboard.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: { layout: 'DefaultLayout' },
    },
    {
        path: '/convert',
        name: 'convert',
        component: Convert,
        meta: { layout: 'DefaultLayout' },
    },
    {
        path: '/about',
        name: 'about',
        component: About,
        meta: { layout: 'DefaultLayout' },
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { layout: 'DefaultLayout' },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { layout: 'DefaultLayout' },
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            layout: 'AuthenticatedLayout',
            requiresAuth: true,
        },
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

import axios from 'axios'

router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth) {
        try {
            await axios.get('/api/user')
            next()
        } catch {
            next('/login')
        }
    } else {
        next()
    }
})

export default router
