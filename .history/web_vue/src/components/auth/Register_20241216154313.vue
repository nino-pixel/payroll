<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

interface RegisterForm {
  name: string
  email: string
  password: string
  password_confirmation: string
  role: 'admin' | 'employee'
}

const router = useRouter()
const auth = useAuthStore()

const form = ref<RegisterForm>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'employee'
})
const error = ref('')
const loading = ref(false)

const register = async () => {
  try {
    loading.value = true
    error.value = ''
    
    if (form.value.password !== form.value.password_confirmation) {
      throw new Error('Passwords do not match')
    }

    await auth.register(form.value)
    router.push('/')
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Registration failed'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="register-container">
    <form @submit.prevent="register" class="register-form">
      <h2>Register</h2>
      
      <div class="form-group">
        <label for="name">Name</label>
        <input 
          type="text" 
          id="name" 
          v-model="form.name" 
          required
          autocomplete="name"
        >
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input 
          type="email" 
          id="email" 
          v-model="form.email" 
          required
          autocomplete="email"
        >
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input 
          type="password" 
          id="password" 
          v-model="form.password" 
          required
          autocomplete="new-password"
        >
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input 
          type="password" 
          id="password_confirmation" 
          v-model="form.password_confirmation" 
          required
          autocomplete="new-password"
        >
      </div>

      <div class="form-group">
        <label for="role">Role</label>
        <select id="role" v-model="form.role" required>
          <option value="employee">Employee</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <div v-if="error" class="error">
        {{ error }}
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Registering...' : 'Register' }}
      </button>

      <div class="login-link">
        Already have an account? 
        <router-link to="/login">Login here</router-link>
      </div>
    </form>
  </div>
</template>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: #f5f5f5;
}

.register-form {
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

input, select {
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
  margin-bottom: 1rem;
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

.login-link {
  text-align: center;
  font-size: 0.9rem;
  color: #666;
}

.login-link a {
  color: #36A2EB;
  text-decoration: none;
}

.login-link a:hover {
  text-decoration: underline;
}
</style> 