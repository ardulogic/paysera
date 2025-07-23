// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

import BookingView from '../views/HomeView.vue'
import AdminView from '../views/AdminView.vue'

const routes = [
    { path: '/', component: BookingView, name: 'home' },
    { path: '/admin', component: AdminView, name: 'admin' },
]

export default createRouter({
    history: createWebHistory(),
    routes,
})
