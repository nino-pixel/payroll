<script setup lang="ts">
import { ref, watch } from 'vue'
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

const passwordValidation = ref({
  length: false,
  uppercase: false,
  lowercase: false,
  number: false
})

const validatePasswordRules = (password: string) => {
  passwordValidation.value = {
    length: password.length >= 8,
    uppercase: /[A-Z]/.test(password),
    lowercase: /[a-z]/.test(password),
    number: /[0-9]/.test(password)
  }
}

const form = ref<RegisterForm>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'employee'
})
const error = ref('')
const loading = ref(false)

// Watch password changes for real-time validation
watch(() => form.value.password, validatePasswordRules)

const register = async () => {
  try {
    loading.value = true
    error.value = ''
    
    if (!Object.values(passwordValidation.value).every(v => v)) {
      throw new Error('Password does not meet the requirements')
    }
    
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
          :class="{ 'invalid': form.password && !Object.values(passwordValidation).every(v => v) }"
        >
        <ul class="password-requirements" v-if="form.password">
          <li :class="{ valid: passwordValidation.length }">
            At least 8 characters
          </li>
          <li :class="{ valid: passwordValidation.uppercase }">
            One uppercase letter
          </li>
          <li :class="{ valid: passwordValidation.lowercase }">
            One lowercase letter
          </li>
          <li :class="{ valid: passwordValidation.number }">
            One number
          </li>
        </ul>
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

.password-requirements {
  list-style: none;
  padding: 0;
  margin: 0.5rem 0;
  font-size: 0.8rem;
  color: #666;
}

.password-requirements li {
  margin: 0.25rem 0;
  padding-left: 1.5rem;
  position: relative;
}

.password-requirements li::before {
  content: '✕';
  position: absolute;
  left: 0;
  color: #dc3545;
}

.password-requirements li.valid::before {
  content: '✓';
  color: #198754;
}

input.invalid {
  border-color: #dc3545;
}

input.invalid:focus {
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}
</style> 