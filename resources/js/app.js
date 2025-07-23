// resources/js/app.js
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import '@style/base.scss';

createApp(App).use(router).mount('#app')
