<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import http from '../utils/http'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

const handleSubmit = async () => {
  try {
    loading.value = true
    error.value = ''
    await http.post('/login', { email: email.value, password: password.value })
    router.push('/dashboard')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Login failed'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-container">
    <div class="login-box">
      <h1>Login</h1>
      
      <form @submit.prevent="handleSubmit" class="login-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input 
            id="email"
            v-model="email"
            type="email"
            required
            autocomplete="email"
          >
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input 
            id="password"
            v-model="password"
            type="password"
            required
            autocomplete="current-password"
          >
        </div>
        
        <div v-if="error" class="error">
          {{ error }}
        </div>
        
        <button 
          type="submit" 
          :disabled="loading"
          class="login-button"
        >
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
        
        <div class="register-link">
          Don't have an account? 
          <router-link to="/register">Register here</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8fafc;
  width: 100vw;
  position: fixed;
  top: 0;
  left: 0;
}

.login-box {
  background: white;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

h1 {
  text-align: center;
  color: #111827;
  margin-bottom: 2rem;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

input {
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1rem;
}

input:focus {
  outline: none;
  border-color: #4f46e5;
  box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
}

.error {
  color: #dc2626;
  font-size: 0.875rem;
  background: #fee2e2;
  padding: 0.75rem;
  border-radius: 0.375rem;
}

.login-button {
  background: #4f46e5;
  color: white;
  padding: 0.75rem;
  border: none;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.login-button:hover {
  background: #4338ca;
}

.login-button:disabled {
  background: #6b7280;
  cursor: not-allowed;
}

.register-link {
  text-align: center;
  font-size: 0.875rem;
  color: #6b7280;
  margin-top: 1rem;
}

.register-link a {
  color: #4f46e5;
  text-decoration: none;
}

.register-link a:hover {
  text-decoration: underline;
}
</style> 