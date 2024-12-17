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
  daily_trend?: Array<{
    date: string
    total: number
    basic_pay: number
    overtime_pay: number
    deductions: number
  }>
}

const stats = ref<PayrollStats | null>(null)
const year = ref(new Date().getFullYear())
const month = ref(new Date().getMonth() + 1)
const selectedStatus = ref<string | null>(null)

const fetchPayrollStats = async () => {
  try {
    const response = await fetch(`/api/v1/payrolls/statistics?year=${year.value}&month=${month.value}&include_trend=true`)
    const data = await response.json()
    stats.value = data.data
  } catch (error) {
    console.error('Error fetching payroll stats:', error)
  }
}

const handleStatusClick = (status: string) => {
  selectedStatus.value = selectedStatus.value === status ? null : status
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

    <div class="trend-chart" v-if="stats?.daily_trend">
      <h3>Daily Payroll Trend</h3>
      <Line
        :data="{
          labels: stats.daily_trend.map(d => d.date),
          datasets: [
            {
              label: 'Total Payroll',
              data: stats.daily_trend.map(d => d.total),
              borderColor: '#36A2EB',
              fill: false
            },
            {
              label: 'Basic Pay',
              data: stats.daily_trend.map(d => d.basic_pay),
              borderColor: '#4BC0C0',
              fill: false
            },
            {
              label: 'Overtime Pay',
              data: stats.daily_trend.map(d => d.overtime_pay),
              borderColor: '#FF6384',
              fill: false
            }
          ]
        }"
        :options="{
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: (context) => {
                  return `${context.dataset.label}: ₱${context.raw.toLocaleString()}`
                }
              }
            }
          }
        }"
      />
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
              backgroundColor: stats.status_summary.map(s => 
                selectedStatus === s.status ? '#FF6384' : '#36A2EB'
              )
            }
          ]
        }"
        :options="{
          responsive: true,
          maintainAspectRatio: false,
          onClick: (_, elements) => {
            if (elements[0]) {
              const index = elements[0].index
              handleStatusClick(stats.status_summary[index].status)
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }"
      />
    </div>

    <div v-if="selectedStatus && stats" class="status-details">
      <h3>Details for {{ selectedStatus }}</h3>
      <div class="details-grid">
        <div class="detail-card">
          <h4>Count</h4>
          <p>{{ stats.status_summary.find(s => s.status === selectedStatus)?.count }}</p>
        </div>
        <div class="detail-card">
          <h4>Total Amount</h4>
          <p>₱{{ stats.status_summary.find(s => s.status === selectedStatus)?.total.toLocaleString() }}</p>
        </div>
      </div>
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

.trend-chart {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  height: 400px;
  margin-bottom: 2rem;
}

.status-details {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin-top: 2rem;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-top: 1rem;
}

.detail-card {
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 6px;
}

.detail-card h4 {
  margin: 0;
  color: #666;
  font-size: 0.9rem;
}

.detail-card p {
  margin: 0.5rem 0 0;
  font-size: 1.4rem;
  font-weight: 600;
  color: #2c3e50;
}
</style> 