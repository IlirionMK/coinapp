<template>
    <section class="space-y-6">
        <h1 class="text-2xl font-bold">{{ $t('admin.users_title') }}</h1>

        <p class="text-gray-700 dark:text-gray-300">
            {{ $t('dashboard.welcome') }}, {{ user.name }} ({{ user.email }})
        </p>

        <AdminUserList />
        <Toast ref="toast" />
    </section>
</template>

<script setup>
import { ref, provide } from 'vue'
import useUser from '@/stores/user'
import AdminUserList from '@/components/admin/AdminUserList.vue'
import Toast from '@/components/ui/Toast.vue'

const { user } = useUser()
const toast = ref()
provide('toast', toast)
</script>
