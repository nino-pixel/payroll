<script setup lang="ts">
import { ref, onMounted } from 'vue'
import http from '../../utils/http'

interface Employee {
  id: number
  name: string
  email: string
  department: {
    name: string
  }
  status: string
}

const employees = ref<Employee[]>([])
const loading = ref(true)
const error = ref('')

const fetchEmployees = async () => {
  try {
    const { data } = await http.get('/employees')
    employees.value = data.data
  } catch (err) {
    error.value = 'Failed to fetch employees'
  } finally {
    loading.value = false
  }
}

onMounted(fetchEmployees)
</script>

<template>
  <div class="employee-list">
    <div class="header">
      <h1>Employees</h1>
      <router-link to="/employees/new" class="btn-primary">
        Add Employee
      </router-link>
    </div>

    <div v-if="loading" class="loading">
      Loading...
    </div>
    
    <div v-else-if="error" class="error">
      {{ error }}
    </div>
    
    <div v-else class="table-container">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee in employees" :key="employee.id">
            <td>{{ employee.name }}</td>
            <td>{{ employee.email }}</td>
            <td>{{ employee.department.name }}</td>
            <td>
              <span :class="['status-badge', employee.status]">
                {{ employee.status }}
              </span>
            </td>
            <td>
              <router-link 
                :to="`/employees/${employee.id}`" 
                class="btn-secondary"
              >
                View
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.employee-list {
  padding: 1rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.table-container {
  background: white;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

th {
  background: #f9fafb;
  font-weight: 500;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  font-size: 0.875rem;
}

.status-badge.active {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.inactive {
  background: #fee2e2;
  color: #991b1b;
}

.loading {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
}

.error {
  color: #dc2626;
  padding: 1rem;
  background: #fee2e2;
  border-radius: 4px;
  margin: 1rem 0;
}

.btn-primary {
  background: #4f46e5;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  text-decoration: none;
}

.btn-secondary {
  background: white;
  border: 1px solid #d1d5db;
  color: #374151;
  padding: 0.25rem 0.75rem;
  border-radius: 0.375rem;
  text-decoration: none;
  font-size: 0.875rem;
}
</style> 