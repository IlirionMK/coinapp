import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { i18n } from './i18n'
import { loadLocaleMessages } from './utils/loadLocaleMessages'

const availableLocales = ['en', 'pl']

let locale = localStorage.getItem('locale') || navigator.language.slice(0, 2)

if (!availableLocales.includes(locale)) {
    locale = 'en'
}

loadLocaleMessages(i18n, locale).then(() => {
    const app = createApp(App)
    app.use(router)
    app.use(i18n)
    document.title = 'CoinApp'
    app.mount('#app')
})
