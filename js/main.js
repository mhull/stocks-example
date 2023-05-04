import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router';

import Main from './vue/Main.vue';
import routes from './routes';
import store from './store';

const app = createApp(Main);

const router = createRouter({
	history: createWebHashHistory(),
	routes,
});

app.use(router);
app.use(store);
app.mount('#main-app-container');
