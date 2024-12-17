<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend)

interface DashboardMetrics {
  total_employees: number
  present_today: number
  total_payroll: number
  average_salary: number
  payroll_trend: {
    date: string
    amount: number
  }[]
}

const metrics = ref<DashboardMetrics | null>(null)

const fetchDashboardData = async () => {
  try {
    const response = await fetch('/api/v1/payrolls/statistics?year=' + new Date().getFullYear())
    const data = await response.json()
    metrics.value = data.data
  } catch (error) {
    console.error('Error fetching dashboard data:', error)
  }
}

onMounted(fetchDashboardData)
</script>

<template>
  <div class="dashboard">
    <div class="metrics-grid" v-if="metrics">
      <div class="metric-card">
        <h3>Total Employees</h3>
        <p class="metric-value">{{ metrics.total_employees }}</p>
      </div>
      <div class="metric-card">
        <h3>Present Today</h3>
        <p class="metric-value">{{ metrics.present_today }}</p>
      </div>
      <div class="metric-card">
        <h3>Total Payroll</h3>
        <p class="metric-value">₱{{ metrics.total_payroll.toLocaleString() }}</p>
      </div>
      <div class="metric-card">
        <h3>Average Salary</h3>
        <p class="metric-value">₱{{ metrics.average_salary.toLocaleString() }}</p>
      </div>
    </div>

    <div class="chart-container" v-if="metrics?.payroll_trend">
      <h3>Payroll Trend</h3>
      <Line
        :data="{
          labels: metrics.payroll_trend.map(d => d.date),
          datasets: [{
            label: 'Monthly Payroll',
            data: metrics.payroll_trend.map(d => d.amount),
            borderColor: '#36A2EB',
            tension: 0.4
          }]
        }"
        :options="{
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: {
              display: true,
              text: 'Monthly Payroll Trend'
            }
          }
        }"
      />
    </div>
  </div>
</template>

<style scoped>
.dashboard {
  padding: 1.5rem;
}

.metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.metric-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.metric-card h3 {
  margin: 0;
  color: #666;
  font-size: 0.9rem;
}

.metric-value {
  margin: 0.5rem 0 0;
  font-size: 1.8rem;
  font-weight: 600;
  color: #2c3e50;
}

.chart-container {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  height: 400px;
}
</style> 