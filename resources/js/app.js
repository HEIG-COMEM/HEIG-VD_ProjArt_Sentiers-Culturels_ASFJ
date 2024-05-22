import { createApp } from 'vue/dist/vue.esm-bundler.js';

import AppDebug from './components/AppDebug.vue';

const app = createApp({});
app.component('app-debug', AppDebug);

app.mount('#app');
