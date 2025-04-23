import { createRouter, createWebHistory } from 'vue-router'
import Layout from '@/layouts/Layout.vue'
import Home from '@/pages/Home.vue'
import About from '@/pages/About.vue'
import Convert from '@/pages/Convert.vue'

const routes = [
    {
        path: '/',
        component: Layout,
        children: [
            { path: '', component: Home },
            { path: 'about', component: About },
            { path: 'convert', component: Convert },
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
