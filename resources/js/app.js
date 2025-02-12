import './bootstrap'
import { createApp } from 'vue'
import router from './router/index'
import Alpine from 'alpinejs'
import App from './App.vue'
import Courses from './dashboard/Courses/Courses.vue'

window.Alpine = Alpine
Alpine.start()

const app = createApp(App)
app.use(router)
app.component('courses', Courses)
app.mount('#app')
