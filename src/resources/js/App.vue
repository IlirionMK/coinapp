<template>
    <component :is="resolvedLayout">
        <router-view />
    </component>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AuthLayout from '@/layouts/AuthenticatedLayout.vue'

 const layouts = {
    DefaultLayout,
    AuthLayout,
}
console.time('App loaded')
console.timeEnd('App loaded')

const route = useRoute()

 const resolvedLayout = computed(() => {
    const name = route.meta?.layout
    return layouts[name] || DefaultLayout
})
</script>
