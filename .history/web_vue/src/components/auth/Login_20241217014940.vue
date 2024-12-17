<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import http from '../../utils/http'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

const handleSubmit = async () => {
  try {
    loading.value = true
    error.value = ''
    
    const response = await http.post('/login', {
      email: email.value,
      password: password.value
    })

    if (response.data.data.token) {
      localStorage.setItem('token', response.data.data.token)
      console.log('Login successful, token:', response.data.data.token) // Debug log
      router.push('/dashboard')
    }
  } catch (err: any) {
    console.error('Login error:', err) // Debug log
    error.value = err.response?.data?.message || 'Login failed'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-container">
    <form @submit.prevent="handleSubmit" class="login-form">
      <h2>Login</h2>
      
      <div class="form-group">
        <label for="email">Email</label>
        <input 
          type="email" 
          id="email" 
          v-model="email" 
          required
          autocomplete="email"
        >
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input 
          type="password" 
          id="password" 
          v-model="password" 
          required
          autocomplete="current-password"
        >
      </div>

      <div v-if="error" class="error">
        {{ error }}
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Logging in...' : 'Login' }}
      </button>
    </form>
    <div class="register-link">
      Don't have an account? 
      <router-link to="/register">Register here</router-link>
    </div>
  </div>
</template>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: #f5f5f5;
}

.login-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 400px;
}

.form-group {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #666;
}

input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

button {
  width: 100%;
  padding: 0.75rem;
  background: #36A2EB;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
}

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.error {
  color: #dc3545;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.register-link {
  text-align: center;
  font-size: 0.9rem;
  color: #666;
  margin-top: 1rem;
}

.register-link a {
  color: #36A2EB;
  text-decoration: none;
}

.register-link a:hover {
  text-decoration: underline;
}
</style> 