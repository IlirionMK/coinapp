<script setup>
import { ref, provide, computed } from 'vue'
import { useRoute } from 'vue-router'

import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import Toast from '@/components/ui/Toast.vue'

const toast = ref()
provide('toast', toast)

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

<template>
    <component :is="resolvedLayout">
        <router-view />
        <Toast ref="toast" />
    </component>
</template>
