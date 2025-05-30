<template>
    <div>
        <div class="flex items-center justify-between mb-4">
            <input
                v-model="search"
                @input="fetchUsers"
                type="text"
                class="border p-2 rounded w-1/3"
                :placeholder="$t('admin.search_placeholder')"
            />

            <select v-model="sort" @change="fetchUsers" class="border p-2 rounded">
                <option value="name">Sort by Name</option>
                <option value="email">Sort by Email</option>
                <option value="created_at">Sort by Date</option>
            </select>
        </div>

        <table class="w-full text-left border">
            <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Name</th>
                <th class="p-2">Email</th>
                <th class="p-2">Registered</th>
                <th class="p-2">Verified</th>
                <th class="p-2">Status</th>
                <th class="p-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users.data" :key="user.id" class="border-t">
                <td class="p-2">{{ user.name }}</td>
                <td class="p-2">{{ user.email }}</td>
                <td class="p-2">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                <td class="p-2">{{ user.email_verified_at ? '✔' : '✖' }}</td>
                <td class="p-2">
                    <span :class="user.is_banned ? 'text-red-600' : 'text-green-600'">
                        {{ user.is_banned ? 'Banned' : 'Active' }}
                    </span>
                </td>
                <td class="p-2 space-x-2">
                    <button
                        @click="confirmBan(user)"
                        class="text-sm text-yellow-600 hover:underline"
                    >
                        {{ user.is_banned ? 'Unban' : 'Ban' }}
                    </button>
                    <button
                        @click="confirmVerify(user)"
                        v-if="!user.email_verified_at"
                        class="text-sm text-blue-600 hover:underline"
                    >
                        Verify
                    </button>
                    <button
                        @click="confirmDelete(user)"
                        class="text-sm text-red-600 hover:underline"
                    >
                        Delete
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="mt-4 flex justify-between items-center">
            <button
                @click="fetchUsers(users.prev_page_url)"
                :disabled="!users.prev_page_url"
                class="text-blue-600 hover:underline disabled:text-gray-400"
            >
                ← Prev
            </button>

            <span class="text-sm">
                Page {{ users.current_page }} of {{ users.last_page }}
            </span>

            <button
                @click="fetchUsers(users.next_page_url)"
                :disabled="!users.next_page_url"
                class="text-blue-600 hover:underline disabled:text-gray-400"
            >
                Next →
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, inject } from 'vue'
import axios from '@/utils/axios'
import { useI18n } from 'vue-i18n'

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
const sort = ref('created_at')

const fetchUsers = async (url = '/admin/users') => {
    // Удаляем домен, если absolute URL от paginator
    const finalUrl = url.replace(/^https?:\/\/[^/]+/, '')

    const response = await axios.get(finalUrl, {
        params: {
            search: search.value,
            sort: sort.value,
            direction: 'asc',
        },
    })
    users.value = response.data
}

const confirmDelete = async (user) => {
    try {
        await axios.delete(`/admin/users/${user.id}`)
        toast.value?.show(`User ${user.email} deleted`, 'success')
        fetchUsers()
    } catch (e) {
        toast.value?.show(`Error deleting user: ${e.response?.data?.message || e.message}`, 'error')
    }
}

const confirmBan = async (user) => {
    try {
        await axios.put(`/admin/users/${user.id}/ban`)
        const action = user.is_banned ? 'unbanned' : 'banned'
        toast.value?.show(`User ${user.email} ${action}`, 'success')
        fetchUsers()
    } catch (e) {
        toast.value?.show(`Error banning user: ${e.response?.data?.message || e.message}`, 'error')
    }
}

const confirmVerify = async (user) => {
    try {
        await axios.put(`/admin/users/${user.id}/verify`)
        toast.value?.show(`Email verified for ${user.email}`, 'success')
        fetchUsers()
    } catch (e) {
        toast.value?.show(`Error verifying email: ${e.response?.data?.message || e.message}`, 'error')
    }
}

onMounted(fetchUsers)
</script>
