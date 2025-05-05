// src/resources/js/router/index.js

import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Convert from '../pages/Convert.vue'
import About from '../pages/About.vue'

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
        component: Convert, // ✅ правильно
        meta: { layout: 'DefaultLayout' },
    },
    {
        path: '/about',
        name: 'about',
        component: About,
        meta: { layout: 'DefaultLayout' },
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
