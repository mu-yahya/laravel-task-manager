import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import TaskTable from './components/TaskTable.vue'
import Login from './Pages/Auth/Login.vue'



import axios from 'axios'

/*axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://localhost:8000'*/

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const app = createApp({

})
app.component('task-table', TaskTable)
app.component('login', Login)
app.mount('#app')

/*createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/** /*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
*/