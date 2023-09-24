import './bootstrap';

import '../css/app.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './routes'
import { createVuetify } from 'vuetify'
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

const vuetify = createVuetify({
    components,
    directives,
})

const pinia = createPinia().use(piniaPluginPersistedstate);

createApp(App).use(router).use(pinia).use(vuetify).mount("#app")
