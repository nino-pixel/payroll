import { defineStore } from 'pinia'
import http from '../utils/http'
import { ref, computed } from 'vue'

export interface User {
  id: number
  name: string
  email: string
  role?: {
    slug: string
    permissions: Array<{ slug: string }>
  }
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('token'))
  const permissions = ref<string[]>([])

  const isAuthenticated = computed(() => !!token.value)
  const hasPermission = (permission: string) => permissions.value.includes(permission)
  const hasRole = (role: string) => user.value?.role?.slug === role

  async function login(credentials: any) {
    try {
      const { data } = await http.post('/auth/login', credentials)
      setAuth(data)
      return data
    } catch (error) {
      throw error.response?.data?.message || 'Login failed'
    }
  }

  async function fetchUser() {
    try {
      const { data } = await http.get('/auth/user')
      user.value = data.data
      permissions.value = data.data.role?.permissions?.map(p => p.slug) || []
    } catch (error) {
      logout()
      throw error
    }
  }

  function setAuth(data: any) {
    token.value = data.token
    user.value = data.user
    permissions.value = data.user.role?.permissions?.map(p => p.slug) || []
    localStorage.setItem('token', data.token)
  }

  function logout() {
    user.value = null
    token.value = null
    permissions.value = []
    localStorage.removeItem('token')
  }

  return {
    user,
    token,
    permissions,
    isAuthenticated,
    hasPermission,
    hasRole,
    login,
    fetchUser,
    setAuth,
    logout
  }
}) 