// resources/js/app.js
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import '@style/base.scss';
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'
dayjs.extend(utc)
dayjs.extend(timezone)

createApp(App).use(router).mount('#app')
