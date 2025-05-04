// src/resources/js/router/index.js

import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Convert from '../pages/Convert.vue'

const routes = [
    { path: '/', name: 'home', component: Home },
    { path: '/convert', name: 'convert', component: Convert },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
