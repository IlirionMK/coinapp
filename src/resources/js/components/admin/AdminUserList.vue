<template>
    <div>
        <div class="flex items-center justify-between mb-4">
            <input
                v-model="search"
                @input="fetchUsers"
                type="text"
                class="border p-2 rounded w-full sm:w-1/3"
                :placeholder="$t('admin.search_placeholder')"
            />
        </div>

        <table class="w-full text-center border text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
            <tr>
                <th class="p-2 cursor-pointer" @click="toggleSort('name')">
                    {{ $t('admin.name') }}
                    <span v-if="sortBy === 'name'">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="p-2 cursor-pointer hidden sm:table-cell" @click="toggleSort('email')">
                    {{ $t('admin.email') }}
                    <span v-if="sortBy === 'email'">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="p-2 cursor-pointer hidden md:table-cell" @click="toggleSort('created_at')">
                    {{ $t('admin.registered') }}
                    <span v-if="sortBy === 'created_at'">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="p-2 hidden md:table-cell">{{ $t('admin.verified') }}</th>
                <th class="p-2 hidden md:table-cell">{{ $t('admin.status') }}</th>
                <th class="p-2">{{ $t('admin.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users.data" :key="user.id" class="border-t">
                <td class="p-2">{{ user.name }}</td>
                <td class="p-2 hidden sm:table-cell">{{ user.email }}</td>
                <td class="p-2 hidden md:table-cell">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                <td class="p-2 hidden md:table-cell">
                    <Check v-if="user.email_verified_at" class="w-4 h-4 text-green-600 inline" />
                    <X v-else class="w-4 h-4 text-red-600 inline" />
                </td>
                <td class="p-2 hidden md:table-cell">
                    <span :class="user.is_banned ? 'text-red-600' : 'text-green-600'">
                        {{ user.is_banned ? $t('admin.banned') : $t('admin.active') }}
                    </span>
                </td>
                <td class="p-2 space-x-2">
                    <button @click="confirmBan(user)">
                        <Ban :class="user.is_banned ? 'text-green-600' : 'text-yellow-600'" class="w-4 h-4" />
                    </button>
                    <button v-if="!user.email_verified_at" @click="confirmVerify(user)">
                        <Mail class="w-4 h-4 text-blue-600" />
                    </button>
                    <button @click="confirmDelete(user)">
                        <Trash class="w-4 h-4 text-red-600" />
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="mt-4 flex justify-between items-center text-sm">
            <button
                @click="fetchUsers(users.prev_page_url)"
                :disabled="!users.prev_page_url"
                class="text-blue-600 hover:underline disabled:text-gray-400"
            >
                ← {{ $t('pagination.prev') }}
            </button>

            <span>
                {{ $t('pagination.page') }} {{ users.current_page }} {{ $t('pagination.of') }} {{ users.last_page }}
            </span>

            <button
                @click="fetchUsers(users.next_page_url)"
                :disabled="!users.next_page_url"
                class="text-blue-600 hover:underline disabled:text-gray-400"
            >
                {{ $t('pagination.next') }} →
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, inject } from 'vue'
import axios from '@/utils/axios'
import { useI18n } from 'vue-i18n'
import { Check, X, Ban, Mail, Trash } from 'lucide-vue-next'

const { t } = useI18n()
const toast = inject('toast')

const users = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    prev_page_url: null,
    next_page_url: null,
})

const search = ref('')
const sortBy = ref('created_at')
const sortDirection = ref('asc')

const fetchUsers = async (url = '/admin/users') => {
    const finalUrl = typeof url === 'string' ? url.replace(/^https?:\/\/[^/]+/, '') : '/admin/users'
    const response = await axios.get(finalUrl, {
        params: {
            search: search.value,
            sort: sortBy.value,
            direction: sortDirection.value,
        },
    })
    users.value = response.data
}

const toggleSort = (field) => {
    if (sortBy.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortBy.value = field
        sortDirection.value = 'asc'
    }
    fetchUsers()
}

const confirmDelete = async (user) => {
    try {
        await axios.delete(`/admin/users/${user.id}`)
        toast.value?.show(t('admin.user_deleted', { email: user.email }), 'success')
        fetchUsers()
    } catch (e) {
        toast.value?.show(t('admin.error_deleting', { message: e.response?.data?.message || e.message }), 'error')
    }
}

const confirmBan = async (user) => {
    try {
        await axios.put(`/admin/users/${user.id}/ban`)
        const action = user.is_banned ? t('admin.unbanned') : t('admin.banned')
        toast.value?.show(t('admin.user_status_changed', { email: user.email, action }), 'success')
        fetchUsers()
    } catch (e) {
        toast.value?.show(t('admin.error_banning', { message: e.response?.data?.message || e.message }), 'error')
    }
}

const confirmVerify = async (user) => {
    try {
        await axios.put(`/admin/users/${user.id}/verify`)
        toast.value?.show(t('admin.email_verified', { email: user.email }), 'success')
        fetchUsers()
    } catch (e) {
        toast.value?.show(t('admin.error_verifying', { message: e.response?.data?.message || e.message }), 'error')
    }
}

onMounted(fetchUsers)
</script>
