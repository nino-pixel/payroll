import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import http from '../utils/http'

interface User {
  id: number
  name: string
  email: string
  role: string
}

interface RegisterData {
  name: string
  email: string
  password: string
  role: string
}

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter()
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<User | null>(JSON.parse(localStorage.getItem('user') || 'null'))

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  async function login(email: string, password: string) {
    const { data } = await http.post('/login', { email, password })
    token.value = data.data.token
    user.value = data.data.user
    localStorage.setItem('token', data.data.token)
    localStorage.setItem('user', JSON.stringify(data.data.user))
  }

  async function logout() {
    try {
      await http.post('/logout')
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      router.push('/login')
    }
  }

  async function register(data: RegisterData) {
    const { data: response } = await http.post('/register', data)
    token.value = response.data.token
    user.value = response.data.user
    localStorage.setItem('token', response.data.token)
    localStorage.setItem('user', JSON.stringify(response.data.user))
  }

  return {
    token,
    user,
    isAuthenticated,
    isAdmin,
    login,
    logout,
    register
  }
}) 