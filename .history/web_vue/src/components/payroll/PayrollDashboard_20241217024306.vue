<template>
  <div class="dashboard">
    <div class="dashboard-header">
      <h1>Payroll Dashboard</h1>
      <button @click="fetchDashboardData" class="refresh-btn" :disabled="loading">
        <span v-if="loading">Refreshing...</span>
        <span v-else>Refresh Data</span>
      </button>
    </div>
    
    <div v-if="loading" class="loading">
      <div class="spinner"></div>
      <p>Loading dashboard data...</p>
    </div>
    
    <div v-else-if="error" class="error">
      <p>{{ error }}</p>
      <button @click="fetchDashboardData" class="retry-btn">Retry</button>
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
            <p class="position">{{ employee.position }}</p>
            <p class="department" v-if="employee.department">
              {{ employee.department.name }}
            </p>
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
  };
}

interface DashboardStats {
  total_employees: number;
  active_employees: number;
  total_departments: number;
  recent_employees: Employee[];
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
    const { data } = await http.get<{ data: DashboardStats }>('/api/dashboard/stats')
    stats.value = data.data
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
  padding: 1.5rem;
  max-width: 1200px;
  margin: 0 auto;
}

.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.refresh-btn {
  padding: 0.5rem 1rem;
  background: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
  transition: background-color 0.2s;
}

.refresh-btn:hover:not(:disabled) {
  background: #45a049;
}

.refresh-btn:disabled {
  background: #cccccc;
  cursor: not-allowed;
}

.dashboard-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.stat-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: transform 0.2s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
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
  margin: 0;
}

.recent-employees {
  margin-top: 1rem;
}

.recent-employees h2 {
  margin-bottom: 1rem;
  color: #2c3e50;
}

.employee-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
}

.employee-card {
  background: white;
  padding: 1rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: transform 0.2s;
}

.employee-card:hover {
  transform: translateY(-2px);
}

.employee-card h4 {
  margin: 0;
  color: #2c3e50;
  font-size: 1.1rem;
}

.employee-card .position {
  margin: 0.5rem 0;
  color: #666;
  font-size: 0.9rem;
}

.employee-card .department {
  margin: 0;
  font-size: 0.9rem;
  color: #0066cc;
  font-weight: 500;
}

.loading {
  text-align: center;
  padding: 2rem;
  color: #666;
}

.spinner {
  width: 40px;
  height: 40px;
  margin: 0 auto 1rem;
  border: 3px solid #f3f3f3;
  border-top: 3px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error {
  text-align: center;
  padding: 2rem;
  color: #dc3545;
}

.retry-btn {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
  transition: background-color 0.2s;
}

.retry-btn:hover {
  background: #c82333;
}

@media (max-width: 768px) {
  .dashboard {
    padding: 1rem;
  }
  
  .dashboard-header {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style> 