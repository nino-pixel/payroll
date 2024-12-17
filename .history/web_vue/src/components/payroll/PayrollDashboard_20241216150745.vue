<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend } from 'chart.js'
import { Line, Bar } from 'vue-chartjs'
import type { PayrollAnalytics } from '@/types'

// Register ChartJS components
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend)

const startDate = ref('')
const endDate = ref('')
const departmentId = ref(null)
const analytics = ref<PayrollAnalytics | null>(null)

const fetchAnalytics = async () => {
  try {
    const response = await fetch(`/api/v1/payrolls/analyze?start_date=${startDate.value}&end_date=${endDate.value}${departmentId.value ? `&department_id=${departmentId.value}` : ''}`)
    analytics.value = await response.json()
  } catch (error) {
    console.error('Error fetching analytics:', error)
  }
}

onMounted(fetchAnalytics)
</script>

<template>
  <div class="payroll-dashboard">
    <div class="filters">
      <input type="date" v-model="startDate" @change="fetchAnalytics" />
      <input type="date" v-model="endDate" @change="fetchAnalytics" />
      <select v-model="departmentId" @change="fetchAnalytics">
        <option value="">All Departments</option>
        <!-- Add department options dynamically -->
      </select>
    </div>

    <div class="metrics" v-if="analytics">
      <div class="metric-card">
        <h3>Total Records</h3>
        <p>{{ analytics.metadata.total_records }}</p>
      </div>
      <div class="metric-card">
        <h3>Anomalies Found</h3>
        <p>{{ analytics.anomalies.statistics.anomalies_found }}</p>
      </div>
    </div>

    <div class="charts" v-if="analytics">
      <div class="chart">
        <h3>Time Series Analysis</h3>
        <Line
          :data="analytics.time_series"
          :options="{
            responsive: true,
            maintainAspectRatio: false
          }"
        />
      </div>
      <div class="chart">
        <h3>Seasonal Patterns</h3>
        <Bar
          :data="analytics.seasonal_patterns"
          :options="{
            responsive: true,
            maintainAspectRatio: false
          }"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
.payroll-dashboard {
  padding: 1rem;
}

.filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}

.metrics {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.metric-card {
  padding: 1rem;
  background: #f5f5f5;
  border-radius: 8px;
  text-align: center;
}

.charts {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 2rem;
}

.chart {
  height: 300px;
  padding: 1rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style> 