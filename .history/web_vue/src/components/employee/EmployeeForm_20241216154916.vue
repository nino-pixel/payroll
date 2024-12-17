<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import http from '../../utils/http'

interface Department {
  id: number
  name: string
}

interface EmployeeForm {
  name: string
  email: string
  department_id: number
  status: 'active' | 'inactive'
  salary: number | null
}

const route = useRoute()
const router = useRouter()
const isEdit = route.params.id !== undefined

const form = ref<EmployeeForm>({
  name: '',
  email: '',
  department_id: 0,
  status: 'active',
  salary: null
})

const departments = ref<Department[]>([])
const loading = ref(false)
const error = ref('')

const fetchDepartments = async () => {
  try {
    const { data } = await http.get('/departments')
    departments.value = data.data
  } catch (err) {
    error.value = 'Failed to fetch departments'
  }
}

const fetchEmployee = async () => {
  if (!isEdit) return
  
  try {
    loading.value = true
    const { data } = await http.get(`/employees/${route.params.id}`)
    form.value = {
      name: data.data.name,
      email: data.data.email,
      department_id: data.data.department_id,
      status: data.data.status,
      salary: data.data.salary
    }
  } catch (err) {
    error.value = 'Failed to fetch employee details'
  } finally {
    loading.value = false
  }
}

const submitForm = async () => {
  try {
    loading.value = true
    error.value = ''

    if (isEdit) {
      await http.put(`/employees/${route.params.id}`, form.value)
    } else {
      await http.post('/employees', form.value)
    }

    router.push('/employees')
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Failed to save employee'
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await fetchDepartments()
  await fetchEmployee()
})
</script>

<template>
  <div class="employee-form">
    <h2>{{ isEdit ? 'Edit Employee' : 'Add Employee' }}</h2>

    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label for="name">Name</label>
        <input 
          type="text" 
          id="name" 
          v-model="form.name" 
          required
        >
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input 
          type="email" 
          id="email" 
          v-model="form.email" 
          required
        >
      </div>

      <div class="form-group">
        <label for="department">Department</label>
        <select 
          id="department" 
          v-model="form.department_id" 
          required
        >
          <option value="">Select Department</option>
          <option 
            v-for="dept in departments" 
            :key="dept.id" 
            :value="dept.id"
          >
            {{ dept.name }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select 
          id="status" 
          v-model="form.status" 
          required
        >
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>

      <div class="form-group">
        <label for="salary">Salary</label>
        <input 
          type="number" 
          id="salary" 
          v-model="form.salary" 
          required
          min="0"
          step="0.01"
        >
      </div>

      <div v-if="error" class="error">
        {{ error }}
      </div>

      <div class="form-actions">
        <button 
          type="button" 
          class="btn-cancel" 
          @click="router.push('/employees')"
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
.employee-form {
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

input, select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
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
</style> 