<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import http from '../../utils/http'

interface DepartmentDetails {
  id: number
  name: string
  code: string | null
  description: string | null
  manager: {
    id: number
    name: string
  } | null
  employees: Array<{
    id: number
    name: string
    email: string
    status: string
    position: string
  }>
  stats: {
    total_employees: number
    active_employees: number
    total_payroll: number
    average_salary: number
  }
}

const route = useRoute()
const router = useRouter()
const department = ref<DepartmentDetails | null>(null)
const loading = ref(false)
const error = ref('')

const fetchDepartmentDetails = async () => {
  try {
    loading.value = true
    const { data } = await http.get(`/departments/${route.params.id}/details`)
    department.value = data.data
  } catch (err) {
    error.value = 'Failed to fetch department details'
  } finally {
    loading.value = false
  }
}

onMounted(fetchDepartmentDetails)
</script>

<template>
  <div class="department-details">
    <div class="header">
      <button class="btn-back" @click="router.back()">
        ← Back
      </button>
      <router-link 
        :to="`/departments/${route.params.id}/edit`" 
        class="btn-edit"
      >
        Edit Department
      </router-link>
    </div>

    <div v-if="loading" class="loading">
      Loading department details...
    </div>

    <div v-else-if="error" class="error">
      {{ error }}
    </div>

    <template v-else-if="department">
      <div class="department-header">
        <h1>{{ department.name }}</h1>
        <div class="department-code" v-if="department.code">
          {{ department.code }}
        </div>
      </div>

      <div class="department-info">
        <div class="info-section">
          <h2>Overview</h2>
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-label">Total Employees</div>
              <div class="stat-value">{{ department.stats.total_employees }}</div>
            </div>
            <div class="stat-card">
              <div class="stat-label">Active Employees</div>
              <div class="stat-value">{{ department.stats.active_employees }}</div>
            </div>
            <div class="stat-card">
              <div class="stat-label">Total Payroll</div>
              <div class="stat-value">₱{{ department.stats.total_payroll.toLocaleString() }}</div>
            </div>
            <div class="stat-card">
              <div class="stat-label">Average Salary</div>
              <div class="stat-value">₱{{ department.stats.average_salary.toLocaleString() }}</div>
            </div>
          </div>
        </div>

        <div class="info-section">
          <h2>Description</h2>
          <p class="description">
            {{ department.description || 'No description available.' }}
          </p>
        </div>

        <div class="info-section">
          <h2>Manager</h2>
          <div class="manager-info" v-if="department.manager">
            <div class="manager-name">{{ department.manager.name }}</div>
          </div>
          <p v-else>No manager assigned</p>
        </div>

        <div class="info-section">
          <h2>Employees</h2>
          <div class="employees-table">
            <table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Position</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="employee in department.employees" :key="employee.id">
                  <td>{{ employee.name }}</td>
                  <td>{{ employee.email }}</td>
                  <td>{{ employee.position }}</td>
                  <td>
                    <span :class="['status-badge', employee.status]">
                      {{ employee.status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<style scoped>
.department-details {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.btn-back {
  padding: 0.5rem 1rem;
  background: none;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
}

.department-header {
  margin-bottom: 2rem;
}

.department-header h1 {
  margin: 0;
  color: #2c3e50;
}

.department-code {
  color: #666;
  font-size: 1rem;
  margin-top: 0.5rem;
}

.info-section {
  margin-bottom: 2rem;
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.info-section h2 {
  margin: 0 0 1rem;
  color: #2c3e50;
  font-size: 1.25rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.stat-card {
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 4px;
  text-align: center;
}

.stat-label {
  color: #666;
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 600;
  color: #2c3e50;
}

.description {
  color: #666;
  line-height: 1.6;
}

.manager-info {
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 4px;
}

.manager-name {
  font-weight: 600;
  color: #2c3e50;
}

.employees-table {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid #eee;
}

th {
  background: #f8f9fa;
  font-weight: 600;
  color: #2c3e50;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 999px;
  font-size: 0.75rem;
}

.status-badge.active {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.inactive {
  background: #fee2e2;
  color: #991b1b;
}
</style> 