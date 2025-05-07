<template>
    <component :is="layout">
        <router-view />
    </component>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AuthLayout from '@/layouts/AuthenticatedLayout.vue'

const route = useRoute()

const layouts = {
    DefaultLayout,
    AuthLayout,
}

const layout = computed(() => {
    const name = route.meta.layout || 'DefaultLayout'
    return layouts[name] || DefaultLayout
})
</script>
