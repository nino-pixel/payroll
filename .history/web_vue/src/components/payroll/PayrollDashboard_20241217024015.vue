<template>
  <div class="dashboard">
    <h1>Payroll Dashboard</h1>
    
    <div v-if="loading" class="loading">
      Loading dashboard data...
    </div>
    
    <div v-else-if="error" class="error">
      {{ error }}
    </div>
    
    <div v-else class="dashboard-content">
      <div class="stats-grid">
        <div class="stat-card">
          <h3>Total Employees</h3>
          <p>{{ stats?.total_employees || 0 }}</p>
        </div>
        <div class="stat-card">
          <h3>Active Employees</h3>
          <p>{{ stats?.active_employees || 0 }}</p>
        </div>
        <div class="stat-card">
          <h3>Total Departments</h3>
          <p>{{ stats?.total_departments || 0 }}</p>
        </div>
      </div>

      <div class="recent-employees" v-if="stats?.recent_employees?.length">
        <h2>Recent Employees</h2>
        <div class="employee-list">
          <div v-for="employee in stats.recent_employees" :key="employee.id" class="employee-card">
            <h4>{{ employee.name }}</h4>
            <p>{{ employee.position }}</p>
            <p class="department">{{ employee.department?.name }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import http from '../../utils/http'

interface Employee {
  id: number;
  name: string;
  position: string;
  department?: {
    id: number;
    name: string;
interface DashboardStats {
  total_employees: number;
  active_employees: number;
}

const router = useRouter()
const auth = useAuthStore()
const stats = ref<DashboardStats | null>(null)
const loading = ref(true)
const error = ref('')

const fetchDashboardData = async () => {
  try {
    loading.value = true
    error.value = ''
    const response = await http.get<DashboardStats>('/api/dashboard/stats')
    stats.value = response.data
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load dashboard data'
    console.error('Dashboard error:', err)
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  if (!auth.isAuthenticated) {
    router.push('/login')
    return
  }
  await fetchDashboardData()
})
</script>

<style scoped>
.dashboard {
  padding: 1rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  margin-top: 1rem;
}

.stat-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-card h3 {
  color: #666;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}

.stat-card p {
  font-size: 1.8rem;
  font-weight: 600;
  color: #2c3e50;
}

.loading, .error {
  text-align: center;
  padding: 2rem;
  color: #666;
}

.error {
  color: #dc3545;
}
</style> 