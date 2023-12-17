/**
 * main.ts
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Components
import App from './App.vue'

// Composables
import { createApp } from 'vue'

// Plugins
import { registerPlugins } from '@/UI/plugins'
import { eventSubscribe } from '@/UI/plugins/eventSubscribe'

const app = createApp(App)

registerPlugins(app)
eventSubscribe(app)

app.mount('#app')
