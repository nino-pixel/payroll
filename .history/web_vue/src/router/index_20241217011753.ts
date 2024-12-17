import { createRouter, createWebHistory } from 'vue-router'
import PayrollDashboard from '@/components/payroll/PayrollDashboard.vue'
import Login from '@/components/auth/Login.vue'
import Registration from '@/components/auth/Registration.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/login'
    },
    {
      path: '/payroll',
      name: 'Payroll',
      component: PayrollDashboard,
      meta: { 
        requiresAuth: true,
        permission: 'payroll.view'
      }
    },
    {
      path: '/departments',
      name: 'Departments',
      component: DepartmentList,
      meta: { 
        requiresAuth: true,
        permission: 'departments.view'
      }
    },
    {
      path: '/departments/create',
      name: 'CreateDepartment',
      component: DepartmentForm,
      meta: { 
        requiresAuth: true,
        permission: 'departments.create'
      }
    },
    {
      path: '/departments/:id',
      name: 'DepartmentDetails',
      component: DepartmentDetails,
      meta: { 
        requiresAuth: true,
        permission: 'departments.view'
      }
    },
    {
      path: '/departments/:id/edit',
      name: 'EditDepartment',
      component: DepartmentForm,
      meta: { 
        requiresAuth: true,
        permission: 'departments.edit'
      }
    },
    {
      path: '/employees',
      name: 'Employees',
      component: EmployeeList,
      meta: { 
        requiresAuth: true,
        permission: 'employees.view'
      }
    },
    {
      path: '/employees/create',
      name: 'CreateEmployee',
      component: EmployeeForm,
      meta: { 
        requiresAuth: true,
        permission: 'employees.create'
      }
    },
    {
      path: '/employees/:id',
      name: 'EmployeeDetails',
      component: EmployeeDetails,
      meta: { 
        requiresAuth: true,
        permission: 'employees.view'
      }
    },
    {
      path: '/employees/:id/edit',
      name: 'EditEmployee',
      component: EmployeeForm,
      meta: { 
        requiresAuth: true,
        permission: 'employees.edit'
      }
    },
    {
      path: '/register',
      name: 'register',
      component: Registration
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: PayrollDashboard,
      meta: { requiresAuth: true }
    }
  ]
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('token')
  
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login')
  } else {
    next()
  }
})

export default router
