import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import { permission } from './directives/permission'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.directive('permission', permission)

app.mount('#app') 