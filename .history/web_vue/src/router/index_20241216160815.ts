import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../components/dashboard/Dashboard.vue'
import PayrollDashboard from '../components/payroll/PayrollDashboard.vue'
import Login from '../components/auth/Login.vue'
import Register from '../components/auth/Register.vue'
import EmployeeList from '../components/employee/EmployeeList.vue'
import EmployeeForm from '../components/employee/EmployeeForm.vue'
import DepartmentList from '../components/department/DepartmentList.vue'
import DepartmentForm from '../components/department/DepartmentForm.vue'
import DepartmentDetails from '../components/department/DepartmentDetails.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/register',
      name: 'register',
      component: Register,
      meta: { guest: true }
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: { guest: true }
    },
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard,
      meta: { requiresAuth: true }
    },
    {
      path: '/payroll',
      name: 'payroll',
      component: PayrollDashboard,
      meta: { requiresAuth: true }
    },
    {
      path: '/employees',
      name: 'employees',
      component: EmployeeList,
      meta: { requiresAuth: true }
    },
    {
      path: '/employees/new',
      name: 'employee-create',
      component: EmployeeForm,
      meta: { requiresAuth: true }
    },
    {
      path: '/employees/:id/edit',
      name: 'employee-edit',
      component: EmployeeForm,
      meta: { requiresAuth: true }
    },
    {
      path: '/departments',
      name: 'departments',
      component: DepartmentList,
      meta: { requiresAuth: true }
    },
    {
      path: '/departments/new',
      name: 'department-create',
      component: DepartmentForm,
      meta: { requiresAuth: true }
    },
    {
      path: '/departments/:id',
      name: 'department-details',
      component: DepartmentDetails,
      meta: { requiresAuth: true }
    },
    {
      path: '/departments/:id/edit',
      name: 'department-edit',
      component: DepartmentForm,
      meta: { requiresAuth: true }
    }
  ]
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  
  if (to.meta.requiresAuth && !token) {
    next('/login')
  } else if (to.meta.guest && token) {
    next('/')
  } else {
    next()
  }
})

export default router
