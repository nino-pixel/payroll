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

const submitForm = async () => {
  try {
    loading.value = true
    error.value = ''

    if (isEdit) {
      await http.put(`/departments/${route.params.id}`, form.value)
    } else {
      await http.post('/departments', form.value)
    }

    router.push('/departments')
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Failed to save department'
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await Promise.all([fetchManagers(), fetchDepartment()])
})
</script>

<template>
  <div class="department-form">
    <h2>{{ isEdit ? 'Edit Department' : 'Add Department' }}</h2>

    <form @submit.prevent="submitForm">
      <div class="form-group">
        <label for="name">Department Name</label>
        <input 
          type="text" 
          id="name" 
          v-model="form.name" 
          required
          placeholder="Enter department name"
        >
      </div>

      <div class="form-group">
        <label for="code">Department Code</label>
        <input 
          type="text" 
          id="code" 
          v-model="form.code"
          placeholder="Enter department code"
        >
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea 
          id="description" 
          v-model="form.description"
          rows="4"
          placeholder="Enter department description"
        ></textarea>
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
</style>