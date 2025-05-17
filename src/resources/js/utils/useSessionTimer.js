import { ref, onMounted, onUnmounted } from 'vue'
import useUser from '@/stores/user'

const IDLE_TIMEOUT = 5 * 60 * 1000      // 5 минут бездействия
const WARNING_TIME = 60 * 1000          // 1 минута на отмену

export function useSessionTimer(router) {
    const { user, logout } = useUser()
    const showWarning = ref(false)
    let idleTimer = null
    let warningTimer = null

    const resetTimers = () => {
        clearTimeout(idleTimer)
        clearTimeout(warningTimer)
        showWarning.value = false

        if (user.value) {
            idleTimer = setTimeout(() => {
                showWarning.value = true
                warningTimer = setTimeout(() => {
                    logout(router)
                }, WARNING_TIME)
            }, IDLE_TIMEOUT)
        }
    }

    const stayLoggedIn = () => {
        resetTimers()
    }

    const activityEvents = ['mousemove', 'keydown', 'click', 'scroll']

    onMounted(() => {
        activityEvents.forEach(e => window.addEventListener(e, resetTimers))
        resetTimers()
    })

    onUnmounted(() => {
        activityEvents.forEach(e => window.removeEventListener(e, resetTimers))
        clearTimeout(idleTimer)
        clearTimeout(warningTimer)
    })

    return {
        showWarning,
        stayLoggedIn
    }
}
