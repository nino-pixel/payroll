<script setup lang="ts">
import { ref, onMounted } from 'vue'
import http from '../../utils/http'

interface Department {
  id: number
  name: string
  code: string | null
  description: string | null
  manager: {
    id: number
    name: string
  } | null
  employees_count: number
  active_employee_count: number
}

const departments = ref<Department[]>([])
const loading = ref(false)
const error = ref('')
const searchQuery = ref('')

const fetchDepartments = async () => {
  try {
    loading.value = true
    const { data } = await http.get('/departments', {
      params: {
        search: searchQuery.value || undefined
      }
    })
    departments.value = data.data
  } catch (err) {
    error.value = 'Failed to fetch departments'
  } finally {
    loading.value = false
  }
}

const deleteDepartment = async (id: number) => {
  if (!confirm('Are you sure? This will affect all employees in this department.')) {
    return
  }

  try {
    loading.value = true
    await http.delete(`/departments/${id}`)
    departments.value = departments.value.filter(dept => dept.id !== id)
  } catch (err) {
    error.value = 'Failed to delete department'
  } finally {
    loading.value = false
  }
}

onMounted(fetchDepartments)
</script>

<template>
  <div class="department-list">
    <div class="header">
      <h2>Departments</h2>
      <router-link to="/departments/new" class="btn-add">
        Add Department
      </router-link>
    </div>

    <div class="filters">
      <div class="search-box">
        <input
          type="text"
          v-model="searchQuery"
          @input="fetchDepartments"
          placeholder="Search departments..."
        >
      </div>
    </div>

    <div v-if="loading" class="loading">
      Loading departments...
    </div>

    <div v-else-if="error" class="error">
      {{ error }}
    </div>

    <div v-else class="grid">
      <div v-for="dept in departments" :key="dept.id" class="department-card">
        <div class="department-header">
          <h3>{{ dept.name }}</h3>
          <div class="actions">
            <router-link :to="`/departments/${dept.id}/edit`" class="btn-edit">
              Edit
            </router-link>
            <button @click="deleteDepartment(dept.id)" class="btn-delete">
              Delete
            </button>
          </div>
        </div>

        <div class="department-code" v-if="dept.code">
          Code: {{ dept.code }}
        </div>

        <div class="department-stats">
          <div class="stat">
            <span class="label">Total Employees</span>
            <span class="value">{{ dept.employees_count }}</span>
          </div>
          <div class="stat">
            <span class="label">Active Employees</span>
            <span class="value">{{ dept.active_employee_count }}</span>
          </div>
        </div>

        <div class="department-manager" v-if="dept.manager">
          <span class="label">Manager:</span>
          <span class="value">{{ dept.manager.name }}</span>
        </div>

        <div class="department-description" v-if="dept.description">
          {{ dept.description }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.department-list {
  padding: 1.5rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.btn-add {
  padding: 0.5rem 1rem;
  background: #36A2EB;
  color: white;
  border-radius: 4px;
  text-decoration: none;
}

.filters {
  margin-bottom: 2rem;
}

.search-box input {
  width: 100%;
  max-width: 300px;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.department-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.department-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1rem;
}

.department-header h3 {
  margin: 0;
  color: #2c3e50;
}

.actions {
  display: flex;
  gap: 0.5rem;
}

.btn-edit, .btn-delete {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  cursor: pointer;
}

.btn-edit {
  background: #36A2EB;
  color: white;
  text-decoration: none;
}

.btn-delete {
  background: #dc3545;
  color: white;
  border: none;
}

.department-code {
  color: #666;
  font-size: 0.875rem;
  margin-bottom: 1rem;
}

.department-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 4px;
}

.stat {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.stat .label {
  font-size: 0.75rem;
  color: #666;
}

.stat .value {
  font-size: 1.25rem;
  font-weight: 600;
  color: #2c3e50;
}

.department-manager {
  margin-bottom: 1rem;
}

.department-manager .label {
  color: #666;
  margin-right: 0.5rem;
}

.department-description {
  color: #666;
  font-size: 0.875rem;
}
</style> 