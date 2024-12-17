<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import http from '../../utils/http'

interface DepartmentForm {
  name: string
  code: string
  description: string
  manager_id: number | null
}

interface FormErrors {
  name?: string
  code?: string
  description?: string
  manager_id?: string
}

const route = useRoute()
const router = useRouter()
const isEdit = route.params.id !== undefined

const form = ref<DepartmentForm>({
  name: '',
  code: '',
  description: '',
  manager_id: null
})

const managers = ref<Array<{ id: number, name: string }>>([])
const loading = ref(false)
const error = ref('')
const errors = ref<FormErrors>({})

const successMessage = ref('')
const showSuccess = (message: string) => {
  successMessage.value = message
  setTimeout(() => {
    successMessage.value = ''
  }, 3000)
}

const confirmDelete = async () => {
  if (!confirm('Are you sure you want to delete this department? This action cannot be undone.')) {
    return
  }

  try {
    loading.value = true
    await http.delete(`/departments/${route.params.id}`)
    showSuccess('Department deleted successfully')
    router.push('/departments')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to delete department'
  } finally {
    loading.value = false
  }
}

const fetchManagers = async () => {
  try {
    const { data } = await http.get('/users', {
      params: { role: 'admin' }
    })
    managers.value = data.data
  } catch (err) {
    error.value = 'Failed to fetch managers'
  }
}

const fetchDepartment = async () => {
  if (!isEdit) return
  
  try {
    loading.value = true
    const { data } = await http.get(`/departments/${route.params.id}`)
    form.value = {
      name: data.data.name,
      code: data.data.code || '',
      description: data.data.description || '',
      manager_id: data.data.manager_id
    }
  } catch (err) {
    error.value = 'Failed to fetch department details'
  } finally {
    loading.value = false
  }
}

const validateForm = (): boolean => {
  errors.value = {}
  let isValid = true

  if (!form.value.name.trim()) {
    errors.value.name = 'Department name is required'
    isValid = false
  } else if (form.value.name.length < 3) {
    errors.value.name = 'Department name must be at least 3 characters'
    isValid = false
  }

  if (form.value.code && form.value.code.length < 2) {
    errors.value.code = 'Department code must be at least 2 characters'
    isValid = false
  }

  if (form.value.description && form.value.description.length > 500) {
    errors.value.description = 'Description cannot exceed 500 characters'
    isValid = false
  }

  return isValid
}

const submitForm = async () => {
  if (!validateForm()) return

  try {
    loading.value = true
    error.value = ''

    if (isEdit) {
      await http.put(`/departments/${route.params.id}`, form.value)
      showSuccess('Department updated successfully')
    } else {
      await http.post('/departments', form.value)
      showSuccess('Department created successfully')
    }

    router.push('/departments')
  } catch (err: any) {
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors
    } else {
      error.value = err.response?.data?.message || 'Failed to save department'
    }
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await Promise.all([fetchManagers(), fetchDepartment()])
})
</script>

<template>
  <div class="department-form" role="form" aria-label="Department Form">
    <div 
      v-if="successMessage" 
      class="success-message" 
      role="alert"
      aria-live="polite"
    >
      {{ successMessage }}
    </div>

    <div 
      v-if="loading" 
      class="loading-overlay" 
      role="status" 
      aria-live="polite"
    >
      <div class="spinner" aria-hidden="true"></div>
      <p>{{ isEdit ? 'Updating' : 'Creating' }} department...</p>
    </div>

    <h2 id="form-title">{{ isEdit ? 'Edit Department' : 'Add Department' }}</h2>

    <form 
      @submit.prevent="submitForm"
      aria-labelledby="form-title"
      novalidate
    >
      <div class="form-group">
        <label for="name" class="required">Department Name</label>
        <input 
          type="text" 
          id="name" 
          v-model="form.name" 
          :class="{ 'invalid': errors.name }"
          :aria-invalid="!!errors.name"
          :aria-describedby="errors.name ? 'name-error' : undefined"
          placeholder="Enter department name"
          required
        >
        <span 
          v-if="errors.name" 
          class="error-text" 
          id="name-error" 
        <span v-if="errors.name" class="error-text">{{ errors.name }}</span>
      </div>

      <div class="form-group">
        <label for="code">Department Code</label>
        <input 
          type="text" 
          id="code" 
          v-model="form.code"
          :class="{ 'invalid': errors.code }"
          placeholder="Enter department code"
        >
        <span v-if="errors.code" class="error-text">{{ errors.code }}</span>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea 
          id="description" 
          v-model="form.description"
          :class="{ 'invalid': errors.description }"
          rows="4"
          placeholder="Enter department description"
        ></textarea>
        <span v-if="errors.description" class="error-text">{{ errors.description }}</span>
        <span class="hint">{{ form.description?.length || 0 }}/500 characters</span>
      </div>

      <div class="form-group">
        <label for="manager">Department Manager</label>
        <select 
          id="manager" 
          v-model="form.manager_id"
        >
          <option value="">Select Manager</option>
          <option 
            v-for="manager in managers" 
            :key="manager.id" 
            :value="manager.id"
          >
            {{ manager.name }}
          </option>
        </select>
      </div>

      <div v-if="error" class="error">
        {{ error }}
      </div>

      <div class="form-actions">
        <button 
          type="button" 
          class="btn-cancel" 
          @click="router.push('/departments')"
        >
          Cancel
        </button>
        <button 
          type="submit" 
          class="btn-submit" 
          :disabled="loading"
        >
          {{ loading ? 'Saving...' : (isEdit ? 'Update' : 'Create') }}
        </button>
      </div>
    </form>
  </div>
</template>

<style scoped>
.department-form {
  max-width: 600px;
  margin: 0 auto;
  padding: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #666;
}

input, select, textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

textarea {
  resize: vertical;
}

.error {
  color: #dc3545;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 2rem;
}

.btn-cancel, .btn-submit {
  padding: 0.5rem 1rem;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
}

.btn-cancel {
  background: #f8f9fa;
  border: 1px solid #ddd;
}

.btn-submit {
  background: #36A2EB;
  color: white;
  border: none;
}

.btn-submit:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.9);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #f3f3f3;
  border-top: 3px solid #36A2EB;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.invalid {
  border-color: #dc3545;
}

.error-text {
  color: #dc3545;
  font-size: 0.8rem;
  margin-top: 0.25rem;
  display: block;
}

.hint {
  color: #666;
  font-size: 0.8rem;
  margin-top: 0.25rem;
  display: block;
}
</style>