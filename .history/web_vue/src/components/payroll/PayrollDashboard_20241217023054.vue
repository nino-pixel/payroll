<template>
  <div class="dashboard">
    <h1>Payroll Dashboard</h1>
    <div class="stats-grid" v-if="stats">
      <div class="stat-card">
        <h3>Total Employees</h3>
        <p>{{ stats.total_employees }}</p>
      </div>
      <div class="stat-card">
        <h3>Active Employees</h3>
        <p>{{ stats.active_employees }}</p>
      </div>
      <!-- Add more stats cards as needed -->
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import http from '@/utils/http'

const router = useRouter()
const stats = ref(null)
const loading = ref(true)
const error = ref('')

const fetchDashboardData = async () => {
  try {
    const response = await http.get('/dashboard/stats')
    stats.value = response.data
  } catch (err) {
    error.value = 'Failed to load dashboard data'
    console.error('Dashboard error:', err)
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      router.push('/login')
      return
    }
    await fetchDashboardData()
  } catch (error) {
    console.error('Dashboard error:', error)
  }
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
</style> 