import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../store/auth'
import PayrollDashboard from '../components/payroll/PayrollDashboard.vue'
import Login from '../views/Login.vue'
import Unauthorized from '../views/Unauthorized.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: { guest: true }
    },
    {
      path: '/unauthorized',
      name: 'Unauthorized',
      component: Unauthorized
    },
    {
      path: '/',
      name: 'Dashboard',
      component: PayrollDashboard,
      meta: { 
        requiresAuth: true,
        permission: 'dashboard.view'
      }
    },
    {
      path: '/payroll',
      name: 'Payroll',
      component: PayrollDashboard,
      meta: { 
        requiresAuth: true,
        permission: 'payroll.view'
      }
    }
  ]
})

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

  // Allow access to guest routes
  if (to.meta.guest) {
    if (auth.isAuthenticated) {
      return next({ name: 'Dashboard' })
    }
    return next()
  }

  // Check authentication
  if (!auth.isAuthenticated && to.meta.requiresAuth) {
    return next({ name: 'Login' })
  }

  // Check permissions
  if (to.meta.permission) {
    const hasPermission = auth.hasPermission(to.meta.permission as string)
    if (!hasPermission) {
      return next({ name: 'Unauthorized' })
    }
  }

  next()
})

export default router
