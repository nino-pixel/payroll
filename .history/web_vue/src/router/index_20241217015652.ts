import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../store/auth'
import PayrollDashboard from '../components/payroll/PayrollDashboard.vue'
import DepartmentList from '../components/department/DepartmentList.vue'
import DepartmentForm from '../components/department/DepartmentForm.vue'
import DepartmentDetails from '../components/department/DepartmentDetails.vue'
import EmployeeList from '../components/employee/EmployeeList.vue'
import EmployeeForm from '../components/employee/EmployeeForm.vue'
import EmployeeDetails from '../components/employee/EmployeeDetails.vue'
import Login from '../views/Login.vue'
import Unauthorized from '../views/Unauthorized.vue'
import Registration from '../views/Registration.vue'

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
