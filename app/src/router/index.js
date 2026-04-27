import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

const routes = [
  {
    path: '/',
    redirect: '/profile',
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/pages/auth/LoginPage.vue'),
    meta: { requiresAuth: false },
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/pages/auth/ProfilePage.vue'),
    meta: { requiresAuth: true, title: 'Mein Profil' },
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return { name: 'login' }
  }

  if (to.meta.requiresAuth === false && authStore.isAuthenticated) {
    return { name: 'profile' }
  }
})

export default router
