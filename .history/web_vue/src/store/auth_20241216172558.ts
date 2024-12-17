import { defineStore } from 'pinia'
import http from '../utils/http'

export interface User {
  id: number
  name: string
  email: string
  role?: {
    slug: string
    permissions: Array<{ slug: string }>
  }
}

export interface AuthState {
  user: User | null
  token: string | null
  permissions: string[]
}

export const useAuthStore = defineStore<string, AuthState>('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token'),
    permissions: []
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    hasPermission: (state) => (permission) => {
      return state.permissions.includes(permission)
    },
    hasRole: (state) => (role) => {
      return state.user?.role?.slug === role
    }
  },

  actions: {
    async login(credentials) {
      try {
        const { data } = await http.post('/auth/login', credentials)
        this.setAuth(data)
        return data
      } catch (error) {
        throw error.response?.data?.message || 'Login failed'
      }
    },

    async fetchUser() {
      try {
        const { data } = await http.get('/auth/user')
        this.user = data.data
        this.permissions = data.data.role?.permissions?.map(p => p.slug) || []
      } catch (error) {
        this.logout()
        throw error
      }
    },

    setAuth(data) {
      this.token = data.token
      this.user = data.user
      this.permissions = data.user.role?.permissions?.map(p => p.slug) || []
      localStorage.setItem('token', data.token)
    },

    logout() {
      this.user = null
      this.token = null
      this.permissions = []
      localStorage.removeItem('token')
    }
  }
}) 