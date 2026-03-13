import { createRouter, createWebHistory } from 'vue-router'
import ProxiesView from '@/views/ProxiesView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'proxies', component: ProxiesView },
  ],
})

export default router