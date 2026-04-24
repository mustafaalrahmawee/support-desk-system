import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth.store.js'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('../pages/auth/LoginPage.vue'),
    meta: { guest: true },
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('../pages/auth/ProfilePage.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/profile/edit',
    name: 'profile-edit',
    component: () => import('../pages/auth/ProfileEditPage.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/users',
    name: 'users-list',
    component: () => import('../pages/users/InternalUsersListPage.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/users/create',
    name: 'users-create',
    component: () => import('../pages/users/InternalUserCreatePage.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/users/:id/edit',
    name: 'users-edit',
    component: () => import('../pages/users/InternalUserEditPage.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/',
    redirect: '/profile',
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'login' }
  }
  if (to.meta.guest && auth.isAuthenticated) {
    return { name: 'profile' }
  }
})

export default router
