import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../components/dashboard/Dashboard.vue'
import PayrollDashboard from '../components/payroll/PayrollDashboard.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard
    },
    {
      path: '/payroll',
      name: 'payroll',
      component: PayrollDashboard
    }
  ]
})

export default router
