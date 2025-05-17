import { createRouter, createWebHistory } from 'vue-router'

import Home from '../pages/Home.vue'
import Convert from '../pages/Convert.vue'
import About from '../pages/About.vue'
import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Dashboard from '../pages/Dashboard.vue'
import AdminDashboard from '../pages/AdminDashboard.vue'
import SessionExpired from '../pages/SessionExpired.vue'


import useUser from '@/stores/user'

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
        meta: {
            layout: 'DefaultLayout',
            guestOnly: true,
        },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            layout: 'DefaultLayout',
            guestOnly: true,
        },
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
    {
        path: '/admin',
        name: 'admin.dashboard',
        component: AdminDashboard,
        meta: {
            layout: 'AdminLayout',
            requiresAuth: true,
            requiresAdmin: true,
        },
    },
    {
        path: '/verify-email',
        name: 'verify-email',
        component: () => import('../pages/VerifyEmail.vue'),
        meta: { layout: 'DefaultLayout' },
    },
    {
        path: '/email-verified',
        name: 'email-verified',
        component: () => import('../pages/EmailVerified.vue'),
        meta: { layout: 'DefaultLayout' },
    },
    {
        path: '/session-expired',
        name: 'session-expired',
        component: SessionExpired,
        meta: {
            layout: 'DefaultLayout',
        },
    },



]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to, from, next) => {
    const { user, fetchUser } = useUser()

    // 🛑 Не загружаем пользователя на этих маршрутах
    const skipFetchUser = to.name === 'verify-email'

    if (!skipFetchUser && user.value === null && (to.meta.requiresAuth || to.meta.requiresAdmin)) {
        await fetchUser()
    }

    const isLoggedIn = !!user.value
    const isAdmin = user.value?.role === 'admin'

    if (to.meta.requiresAuth && !isLoggedIn) {
        return next('/login')
    }

    if (to.meta.guestOnly && isLoggedIn) {
        return next(isAdmin ? '/admin' : '/dashboard')
    }

    if (to.meta.requiresAdmin && !isAdmin) {
        return next('/dashboard')
    }

    return next()
})

export default router
