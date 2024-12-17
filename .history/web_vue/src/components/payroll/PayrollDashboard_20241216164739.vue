<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend } from 'chart.js'
import { Line, Bar } from 'vue-chartjs'
import http from '../../utils/http'
import ProcessPayroll from './ProcessPayroll'
import Modal from '../shared/Modal.vue'

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
const loading = ref(false)
const showProcessDialog = ref(false)
const error = ref('')
const errorMessage = ref('')
const showErrorModal = ref(false)

const fetchPayrollStats = async () => {
  try {
    loading.value = true
    error.value = ''
    const { data } = await http.get('/payrolls/statistics', {
      params: {
        year: year.value,
        month: month.value,
        include_trend: true
      }
    })
    stats.value = data.data
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Failed to fetch payroll statistics'
  } finally {
    loading.value = false
  }
}

const handleStatusClick = (status: string) => {
  selectedStatus.value = selectedStatus.value === status ? null : status
}

onMounted(fetchPayrollStats)
</script>

<template>
  <div class="payroll-dashboard">
    <div class="header">
      <h1>Payroll Dashboard</h1>
      <div class="actions">
        <button 
          v-permission="'payroll.process'"
          @click="showProcessDialog" 
          class="btn-primary"
        >
          Process Payroll
        </button>
      </div>
    </div>

    <div class="filters">
      <input 
        v-model="filters.search" 
        placeholder="Search payrolls..."
        class="search-input"
      />
      <select v-model="filters.status">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="processed">Processed</option>
        <option value="approved">Approved</option>
      </select>
    </div>

    <div class="payroll-list">
      <div v-if="loading" class="loading">Loading...</div>
      <div v-else-if="error" class="error">{{ error }}</div>
      <template v-else>
        <table>
          <thead>
            <tr>
              <th>Employee</th>
              <th>Period</th>
              <th>Net Pay</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="payroll in payrolls" :key="payroll.id">
              <td>{{ payroll.employee.user.name }}</td>
              <td>{{ formatDate(payroll.pay_period_start) }}</td>
              <td>{{ formatCurrency(payroll.net_pay) }}</td>
              <td>
                <span :class="['status-badge', payroll.status]">
                  {{ payroll.status }}
                </span>
              </td>
              <td class="actions">
                <button 
                  v-permission="'payroll.view'"
                  @click="viewPayroll(payroll)"
                  class="btn-view"
                >
                  View
                </button>
                <button 
                  v-if="payroll.status === 'pending'"
                  v-permission="'payroll.approve'"
                  @click="approvePayroll(payroll)"
                  class="btn-approve"
                >
                  Approve
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="pagination">
          <button 
            :disabled="currentPage === 1"
            @click="changePage(currentPage - 1)"
          >
            Previous
          </button>
          <span>Page {{ currentPage }} of {{ totalPages }}</span>
          <button 
            :disabled="currentPage === totalPages"
            @click="changePage(currentPage + 1)"
          >
            Next
          </button>
        </div>
      </template>
    </div>

    <ProcessPayroll 
      v-if="showProcessDialog"
      @close="showProcessDialog = false"
      @processed="fetchPayrollStats"
    />

    <!-- Error Modal -->
    <modal v-if="showErrorModal" @close="showErrorModal = false">
      <template #header>
        <h3>Error</h3>
      </template>
      <template #body>
        <p>{{ errorMessage }}</p>
      </template>
      <template #footer>
        <button @click="showErrorModal = false">Close</button>
      </template>
    </modal>
  </div>
</template>

<style scoped>
.payroll-dashboard {
  padding: 1rem;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
}

.status-badge.pending {
  background: #fef3c7;
  color: #92400e;
}

.status-badge.processed {
  background: #e0e7ff;
  color: #3730a3;
}

.status-badge.approved {
  background: #d1fae5;
  color: #065f46;
}

.error {
  color: #dc2626;
  padding: 1rem;
  background: #fee2e2;
  border-radius: 4px;
  margin: 1rem 0;
}
</style> 