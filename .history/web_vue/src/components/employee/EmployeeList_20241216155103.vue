<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
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
const searchQuery = ref('')
const selectedDepartment = ref<number | null>(null)
const selectedStatus = ref<'all' | 'active' | 'inactive'>('all')
const departments = ref<Array<{ id: number, name: string }>>([])

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

const deleteEmployee = async (id: number) => {
  if (!confirm('Are you sure you want to delete this employee?')) return

  try {
    loading.value = true
    await http.delete(`/employees/${id}`)
    employees.value = employees.value.filter(emp => emp.id !== id)
  } catch (err) {
    error.value = 'Failed to delete employee'
  } finally {
    loading.value = false
  }
}

const fetchDepartments = async () => {
  try {
    const { data } = await http.get('/departments')
    departments.value = data.data
  } catch (err) {
    console.error('Failed to fetch departments:', err)
  }
}

const filteredEmployees = computed(() => {
  return employees.value.filter(employee => {
    const matchesSearch = employee.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         employee.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesDepartment = !selectedDepartment.value || employee.department.id === selectedDepartment.value
    const matchesStatus = selectedStatus.value === 'all' || employee.status === selectedStatus.value
    
    return matchesSearch && matchesDepartment && matchesStatus
  })
})

onMounted(async () => {
  await Promise.all([fetchEmployees(), fetchDepartments()])
})
</script>

<template>
  <div class="employee-list">
    <div class="header">
      <h2>Employees</h2>
      <router-link to="/employees/new" class="btn-add">
        Add Employee
      </router-link>
    </div>

    <div class="filters">
      <div class="search-box">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by name or email"
        >
      </div>

      <div class="filter-group">
        <select v-model="selectedDepartment">
          <option :value="null">All Departments</option>
          <option
            v-for="dept in departments"
            :key="dept.id"
            :value="dept.id"
          >
            {{ dept.name }}
          </option>
        </select>

        <select v-model="selectedStatus">
          <option value="all">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="loading">
      Loading employees...
    </div>

    <div v-else-if="error" class="error">
      {{ error }}
    </div>

    <div v-else class="table-container">
      <div v-if="filteredEmployees.length === 0" class="no-results">
        No employees found matching your criteria
      </div>

      <table v-else>
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
          <tr v-for="employee in filteredEmployees" :key="employee.id">
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

.filters {
  margin-bottom: 2rem;
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
}

.search-box {
  flex: 1;
  min-width: 200px;
}

.search-box input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.filter-group {
  display: flex;
  gap: 1rem;
}

.filter-group select {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.no-results {
  text-align: center;
  padding: 2rem;
  color: #666;
}
</style> 