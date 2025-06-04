<template>
    <router-view />
    <Toast ref="toast" />
</template>

<script setup>
import { ref, provide } from 'vue'
import Toast from '@/components/ui/Toast.vue'

const toast = ref()
provide('toast', toast)
</script>
