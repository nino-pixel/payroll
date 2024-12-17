<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend } from 'chart.js'
import { Line, Bar } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend)

interface PayrollStats {
  total_payroll: number
  total_basic_pay: number
  total_overtime_pay: number
  total_deductions: number
  payroll_count: number
  status_summary: Array<{
    status: string
    count: number
    total: number
  }>
}

const stats = ref<PayrollStats | null>(null)
const year = ref(new Date().getFullYear())
const month = ref(new Date().getMonth() + 1)

const fetchPayrollStats = async () => {
  try {
    const response = await fetch(`/api/v1/payrolls/statistics?year=${year.value}&month=${month.value}`)
    const data = await response.json()
    stats.value = data.data
  } catch (error) {
    console.error('Error fetching payroll stats:', error)
  }
}

onMounted(fetchPayrollStats)
</script>

<template>
  <div class="payroll-dashboard">
    <div class="filters">
      <select v-model="year" @change="fetchPayrollStats">
        <option v-for="y in [2023, 2024]" :key="y" :value="y">{{ y }}</option>
      </select>
      <select v-model="month" @change="fetchPayrollStats">
        <option v-for="m in 12" :key="m" :value="m">
          {{ new Date(2000, m-1).toLocaleString('default', { month: 'long' }) }}
        </option>
      </select>
    </div>

    <div class="stats-grid" v-if="stats">
      <div class="stat-card">
        <h3>Total Payroll</h3>
        <p class="stat-value">₱{{ stats.total_payroll.toLocaleString() }}</p>
      </div>
      <div class="stat-card">
        <h3>Basic Pay</h3>
        <p class="stat-value">₱{{ stats.total_basic_pay.toLocaleString() }}</p>
      </div>
      <div class="stat-card">
        <h3>Overtime Pay</h3>
        <p class="stat-value">₱{{ stats.total_overtime_pay.toLocaleString() }}</p>
      </div>
      <div class="stat-card">
        <h3>Deductions</h3>
        <p class="stat-value">₱{{ stats.total_deductions.toLocaleString() }}</p>
      </div>
    </div>

    <div class="status-summary" v-if="stats?.status_summary">
      <h3>Payroll Status Summary</h3>
      <Bar
        :data="{
          labels: stats.status_summary.map(s => s.status),
          datasets: [
            {
              label: 'Count',
              data: stats.status_summary.map(s => s.count),
              backgroundColor: '#36A2EB'
            },
            {
              label: 'Total Amount',
              data: stats.status_summary.map(s => s.total),
              backgroundColor: '#FF6384'
            }
          ]
        }"
        :options="{
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }"
      />
    </div>
  </div>
</template>

<style scoped>
.payroll-dashboard {
  padding: 1.5rem;
}

.filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}

.filters select {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-card h3 {
  margin: 0;
  color: #666;
  font-size: 0.9rem;
}

.stat-value {
  margin: 0.5rem 0 0;
  font-size: 1.8rem;
  font-weight: 600;
  color: #2c3e50;
}

.status-summary {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  height: 400px;
  margin-top: 2rem;
}

.status-summary h3 {
  margin: 0 0 1rem;
  color: #2c3e50;
}
</style> 