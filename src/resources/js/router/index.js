import { createRouter, createWebHistory } from 'vue-router'

import Home from '../pages/Home.vue'
import Convert from '../pages/Convert.vue'
import About from '../pages/About.vue'
import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Dashboard from '../pages/Dashboard.vue'
import AdminDashboard from '../pages/AdminDashboard.vue'
import SessionExpired from '../pages/SessionExpired.vue'
import Profile from '../pages/Profile.vue'
import ChangePassword from '../pages/ChangePassword.vue'
import ForgotPassword from '../pages/ForgotPassword.vue'
import ResetPassword from '../pages/ResetPassword.vue'

import useUser from '@/stores/user'

const routes = [
    { path: '/', name: 'home', component: Home, meta: { layout: 'DefaultLayout' } },
    { path: '/convert', name: 'convert', component: Convert, meta: { layout: 'DefaultLayout' } },
    { path: '/about', name: 'about', component: About, meta: { layout: 'DefaultLayout' } },
    { path: '/login', name: 'login', component: Login, meta: { layout: 'DefaultLayout', guestOnly: true } },
    { path: '/register', name: 'register', component: Register, meta: { layout: 'DefaultLayout', guestOnly: true } },
    { path: '/forgot-password', name: 'forgot-password', component: ForgotPassword, meta: { layout: 'DefaultLayout', guestOnly: true } },
    { path: '/reset-password/:token', name: 'reset-password', component: ResetPassword, meta: { layout: 'DefaultLayout', guestOnly: true } },
    { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { layout: 'AuthenticatedLayout', requiresAuth: true } },
    { path: '/profile', name: 'profile', component: Profile, meta: { layout: 'AuthenticatedLayout', requiresAuth: true } },
    { path: '/profile/password', name: 'profile.password', component: ChangePassword, meta: { layout: 'AuthenticatedLayout', requiresAuth: true } },
    { path: '/admin', name: 'admin.dashboard', component: AdminDashboard, meta: { layout: 'AdminLayout', requiresAuth: true, requiresAdmin: true } },
    { path: '/verify-email', name: 'verify-email', component: () => import('../pages/VerifyEmail.vue'), meta: { layout: 'DefaultLayout' } },
    { path: '/email-verified', name: 'email-verified', component: () => import('../pages/EmailVerified.vue'), meta: { layout: 'DefaultLayout' } },
    { path: '/session-expired', name: 'session-expired', component: SessionExpired, meta: { layout: 'DefaultLayout' } },
    { path: '/403', name: 'forbidden', component: () => import('@/pages/errors/Forbidden.vue') },
    { path: '/news', name: 'news', component: () => import('@/pages/News.vue'), meta: { requiresAuth: false } },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to, from, next) => {
    const { user, fetchUser } = useUser()

    const skipFetchUser = to.name === 'verify-email'
    const needsAuth = to.meta.requiresAuth || to.meta.requiresAdmin

    let isLoggedIn = !!user.value
    let isAdmin = user.value?.role === 'admin'

    if (!skipFetchUser && needsAuth && user.value === null) {
        await fetchUser()
        isLoggedIn = !!user.value
        isAdmin = user.value?.role === 'admin'
    }

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
