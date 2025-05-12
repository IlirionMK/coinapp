<template>
    <component :is="resolvedLayout">
        <router-view />
    </component>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

const layouts = {
    DefaultLayout,
    AuthenticatedLayout,
    AdminLayout,
}

const route = useRoute()

const resolvedLayout = computed(() => {
    const name = route.meta?.layout
    return layouts[name] || DefaultLayout
})
</script>
