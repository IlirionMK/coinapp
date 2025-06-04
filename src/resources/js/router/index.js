import { createRouter, createWebHistory } from 'vue-router'

import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

import useUser from '@/stores/user'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: DefaultLayout,
            children: [
                { path: '', name: 'home', component: () => import('@/pages/Home.vue') },
                { path: 'convert', name: 'convert', component: () => import('@/pages/Convert.vue') },
                { path: 'about', name: 'about', component: () => import('@/pages/About.vue') },
                { path: 'login', name: 'login', component: () => import('@/pages/Login.vue'), meta: { guestOnly: true } },
                { path: 'register', name: 'register', component: () => import('@/pages/Register.vue'), meta: { guestOnly: true } },
                { path: 'forgot-password', name: 'forgot-password', component: () => import('@/pages/ForgotPassword.vue'), meta: { guestOnly: true } },
                { path: 'reset-password/:token', name: 'reset-password', component: () => import('@/pages/ResetPassword.vue'), meta: { guestOnly: true } },
                { path: 'verify-email', name: 'verify-email', component: () => import('@/pages/VerifyEmail.vue') },
                { path: 'email-verified', name: 'email-verified', component: () => import('@/pages/EmailVerified.vue') },
                { path: 'session-expired', name: 'session-expired', component: () => import('@/pages/SessionExpired.vue') },
                { path: 'news', name: 'news', component: () => import('@/pages/News.vue') },
                { path: '403', name: 'forbidden', component: () => import('@/pages/errors/Forbidden.vue') },
            ],
        },
        {
            path: '/',
            component: AuthenticatedLayout,
            meta: { requiresAuth: true },
            children: [
                { path: 'dashboard', name: 'dashboard', component: () => import('@/pages/Dashboard.vue') },
                { path: 'profile', name: 'profile', component: () => import('@/pages/Profile.vue') },
                { path: 'profile/password', name: 'profile.password', component: () => import('@/pages/ChangePassword.vue') },
            ],
        },
        {
            path: '/admin',
            component: AdminLayout,
            meta: { requiresAuth: true, requiresAdmin: true },
            children: [
                { path: '', name: 'admin.dashboard', component: () => import('@/pages/AdminDashboard.vue') },
                { path: 'users', name: 'admin.users', component: () => import('@/components/admin/AdminUserList.vue') },
            ],
        },
    ],
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
