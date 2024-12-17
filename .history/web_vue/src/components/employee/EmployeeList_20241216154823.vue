<script setup lang="ts">
import { ref, onMounted } from 'vue'
import http from '../../utils/http'

interface Employee {
  id: number
  name: string
  email: string
  department: {
    id: number
    name: string
  }
  status: 'active' | 'inactive'
  salary: number
}

const employees = ref<Employee[]>([])
const loading = ref(false)
const error = ref('')

const fetchEmployees = async () => {
  try {
    loading.value = true
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
      <h2>Employees</h2>
      <router-link to="/employees/new" class="btn-add">
        Add Employee
      </router-link>
    </div>

    <div v-if="loading" class="loading">
      Loading employees...
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
            <th>Salary</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee in employees" :key="employee.id">
            <td>{{ employee.name }}</td>
            <td>{{ employee.email }}</td>
            <td>{{ employee.department.name }}</td>
            <td>
              <span :class="['status', employee.status]">
                {{ employee.status }}
              </span>
            </td>
            <td>₱{{ employee.salary.toLocaleString() }}</td>
            <td class="actions">
              <router-link :to="`/employees/${employee.id}/edit`" class="btn-edit">
                Edit
              </router-link>
              <button class="btn-delete" @click="deleteEmployee(employee.id)">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.employee-list {
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

.table-container {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #eee;
}

th {
  background: #f8f9fa;
  font-weight: 600;
}

.status {
  padding: 0.25rem 0.5rem;
  border-radius: 999px;
  font-size: 0.875rem;
}

.status.active {
  background: #d1fae5;
  color: #065f46;
}

.status.inactive {
  background: #fee2e2;
  color: #991b1b;
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
</style> 