import '../css/app.css'  // Подключаем Tailwind
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import i18n from './components/i18n'

const app = createApp(App)

app.use(router)
app.use(i18n)
app.mount('#app')
