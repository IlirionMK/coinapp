<template>
    <section class="min-h-screen bg-gray-100 py-8 px-4">
        <div class="max-w-6xl mx-auto space-y-6">
            <div class="flex justify-between items-center">
                <div class="space-x-4">
                    <RouterLink to="/" class="text-blue-600 hover:underline text-sm">
                        ← {{ $t('nav.home') }}
                    </RouterLink>
                    <RouterLink to="/profile" class="text-blue-600 hover:underline text-sm">
                        {{ $t('dashboard.edit_profile') }}
                    </RouterLink>
                </div>
                <LanguageSwitcher />
            </div>

            <h1 class="text-2xl font-bold">{{ $t('admin.users_title') }}</h1>

            <p class="text-gray-600">
                Welcome, {{ user.name }} ({{ user.email }})
            </p>

            <AdminUserList />
        </div>
        <Toast ref="toast" />
    </section>
</template>

<script setup>
import Toast from '@/components/ui/Toast.vue'
import { ref, provide } from 'vue'

const toast = ref()
provide('toast', toast)

import { RouterLink } from 'vue-router'
import useUser from '@/stores/user'
import LanguageSwitcher from '@/components/ui/LanguageSwitcher.vue'
import AdminUserList from '@/components/admin/AdminUserList.vue'

const { user } = useUser()
</script>
