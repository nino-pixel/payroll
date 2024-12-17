<script lang="ts">
import { ref, onMounted, defineComponent } from 'vue'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend } from 'chart.js'
import { Line, Bar } from 'vue-chartjs'
import http from '../../utils/http'
import ProcessPayroll from './ProcessPayroll.vue'
import Modal from '../shared/Modal.vue'
import LoadingSpinner from '../shared/LoadingSpinner.vue'
import LoadingOverlay from '../shared/LoadingOverlay.vue'
import { exportToExcel, exportToCSV, formatPayrollsForExport, ExportFormat } from '../../utils/export'

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
  department_stats?: Array<{
    name: string
    total: number
    employee_count: number
  }>
}

interface Payroll {
  id: number
  employee: {
    user: {
      name: string
    }
  }
  pay_period_start: string
  net_pay: number
  status: string
}

interface ChartData {
  labels: string[]
  datasets: Array<{
    label: string
    data: number[]
    borderColor?: string
    backgroundColor?: string
  }>
}

interface DepartmentStats {
  name: string
  total: number
  employee_count: number
}

export default defineComponent({
  name: 'PayrollDashboard',
  components: { ProcessPayroll, Modal, LoadingSpinner, LoadingOverlay, Line, Bar },
  setup() {
    const stats = ref<PayrollStats | null>(null)
    const year = ref(new Date().getFullYear())
    const month = ref(new Date().getMonth() + 1)
    const selectedStatus = ref<string | null>(null)
    const loading = ref(false)
    const initialLoading = ref(true)
    const showProcessDialog = ref(false)
    const payrolls = ref<Payroll[]>([])
    const currentPage = ref(1)
    const totalPages = ref(1)
    const filters = ref({
      search: '',
      status: ''
    })
    const error = ref('')
    const errorMessage = ref('')
    const showErrorModal = ref(false)
    const chartData = ref<ChartData>({
      labels: [],
      datasets: []
    })
    const departmentChartData = ref<ChartData>({
      labels: [],
      datasets: []
    })
    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom' as const
        },
        tooltip: {
          callbacks: {
            label: function(context: any) {
              let label = context.dataset.label || '';
              if (label) {
                label += ': ';
              }
              label += formatCurrency(context.parsed.y);
              return label;
            }
          }
        }
      },
      scales: {
        y: {
          ticks: {
            callback: function(value: string | number) {
              if (typeof value === 'number') {
                return formatCurrency(value);
              }
              return value;
            }
          }
        }
      }
    }
    const filterOptions = ref({
      year: new Date().getFullYear(),
      month: new Date().getMonth() + 1,
      department: ''
    })
    const departments = ref<Array<{ id: string, name: string }>>([])
    const exporting = ref(false)
    const exportFormat = ref<ExportFormat>('excel')

    const fetchPayrollStats = async () => {
      try {
        loading.value = true
        error.value = ''
        const { data } = await http.get('/payrolls/statistics', {
          params: {
            year: filterOptions.value.year,
            month: filterOptions.value.month,
            department: filterOptions.value.department,
            include_trend: true
          }
        })
        stats.value = data.data
        prepareChartData()
        prepareDepartmentChart()
      } catch (err) {
        error.value = err instanceof Error ? err.message : 'Failed to fetch payroll statistics'
      } finally {
        loading.value = false
        initialLoading.value = false
      }
    }

    const fetchPayrolls = async () => {
      try {
        loading.value = true
        const { data } = await http.get('/payrolls', {
          params: {
            page: currentPage.value,
            search: filters.value.search,
            status: filters.value.status
          }
        })
        payrolls.value = data.data
        totalPages.value = Math.ceil(data.meta.total / data.meta.per_page)
      } catch (err) {
        error.value = err instanceof Error ? err.message : 'Failed to fetch payrolls'
      } finally {
        loading.value = false
      }
    }

    const handleStatusClick = (status: string) => {
      selectedStatus.value = selectedStatus.value === status ? null : status
    }

    const formatCurrency = (amount: number) => {
      return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
      }).format(amount)
    }

    const formatDate = (date: string) => {
      return new Date(date).toLocaleDateString()
    }

    const viewPayroll = (payroll: Payroll) => {
      console.log('View payroll:', payroll)
    }

    const approvePayroll = async (payroll: Payroll) => {
      try {
        await http.post(`/payrolls/${payroll.id}/approve`)
        await fetchPayrolls()
      } catch (err: any) {
        errorMessage.value = err.response?.data?.message || 'Failed to approve payroll'
        showErrorModal.value = true
      }
    }

    const changePage = (page: number) => {
      currentPage.value = page
      fetchPayrolls()
    }

    const prepareChartData = () => {
      if (!stats.value?.daily_trend) return

      chartData.value = {
        labels: stats.value.daily_trend.map(d => formatDate(d.date)),
        datasets: [
          {
            label: 'Total Pay',
            data: stats.value.daily_trend.map(d => d.total),
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79, 70, 229, 0.1)'
          },
          {
            label: 'Basic Pay',
            data: stats.value.daily_trend.map(d => d.basic_pay),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)'
          },
          {
            label: 'Overtime Pay',
            data: stats.value.daily_trend.map(d => d.overtime_pay),
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245, 158, 11, 0.1)'
          }
        ]
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

    const prepareDepartmentChart = () => {
      if (!stats.value?.department_stats) return

      departmentChartData.value = {
        labels: stats.value.department_stats.map(d => d.name),
        datasets: [{
          label: 'Department Total',
          data: stats.value.department_stats.map(d => d.total),
          backgroundColor: '#4f46e5'
        }]
      }
    }

    const handleFilterChange = () => {
      fetchPayrollStats()
    }

    const handleExport = async (format: ExportFormat) => {
      try {
        exporting.value = true
        exportFormat.value = format

        const formattedData = formatPayrollsForExport(payrolls.value)
        const filename = `payroll-${filterOptions.value.year}-${String(filterOptions.value.month).padStart(2, '0')}`

        if (!stats.value) {
          throw new Error('Statistics not available')
        }

        if (format === 'excel') {
          exportToExcel(formattedData, stats.value, filename)
        } else {
          exportToCSV(formattedData, stats.value, filename)
        }
      } catch (err) {
        error.value = err instanceof Error ? err.message : 'Failed to export payroll data'
        showErrorModal.value = true
      } finally {
        exporting.value = false
      }
    }

    onMounted(() => {
      fetchPayrolls()
      fetchPayrollStats()
      fetchDepartments()
    })

    return {
      stats,
      loading,
      initialLoading,
      showProcessDialog,
      payrolls,
      currentPage,
      totalPages,
      filters,
      error,
      errorMessage,
      showErrorModal,
      fetchPayrolls,
      fetchPayrollStats,
      handleStatusClick,
      formatCurrency,
      formatDate,
      viewPayroll,
      approvePayroll,
      changePage,
      chartData,
      chartOptions,
      departmentChartData,
      filterOptions,
      departments,
      handleFilterChange,
      handleExport,
      exporting,
      exportFormat
    }
  }
})
</script>

<template>
  <div class="payroll-dashboard">
    <LoadingOverlay 
      v-if="initialLoading" 
      message="Loading payroll data..." 
    />

    <div class="header">
      <h1>Payroll Dashboard</h1>
      <div class="actions">
        <div class="export-group">
          <button 
            v-permission="'payroll.export'"
            @click="handleExport('excel')"
            class="btn-secondary"
            :disabled="exporting"
          >
            <LoadingSpinner v-if="exporting && exportFormat === 'excel'" size="small" color="#374151" />
            <span>{{ exporting && exportFormat === 'excel' ? 'Exporting...' : 'Export Excel' }}</span>
          </button>
          <button 
            v-permission="'payroll.export'"
            @click="handleExport('csv')"
            class="btn-secondary"
            :disabled="exporting"
          >
            <LoadingSpinner v-if="exporting && exportFormat === 'csv'" size="small" color="#374151" />
            <span>{{ exporting && exportFormat === 'csv' ? 'Exporting...' : 'Export CSV' }}</span>
          </button>
        </div>
        <button 
          v-permission="'payroll.process'"
          @click="() => showProcessDialog = true"
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
      <div v-if="loading && !initialLoading" class="loading">
        <LoadingSpinner />
        <span>Loading...</span>
      </div>
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

    <div v-if="stats" class="stats-grid">
      <div class="stat-card">
        <h3>Total Payroll</h3>
        <p>{{ formatCurrency(stats.total_payroll) }}</p>
      </div>
      <div class="stat-card">
        <h3>Basic Pay</h3>
        <p>{{ formatCurrency(stats.total_basic_pay) }}</p>
      </div>
      <div class="stat-card">
        <h3>Overtime Pay</h3>
        <p>{{ formatCurrency(stats.total_overtime_pay) }}</p>
      </div>
      <div class="stat-card">
        <h3>Deductions</h3>
        <p>{{ formatCurrency(stats.total_deductions) }}</p>
      </div>
    </div>

    <div class="filter-controls">
      <div class="filter-group">
        <label>Year</label>
        <select v-model="filterOptions.year" @change="handleFilterChange">
          <option v-for="y in 5" :key="y" :value="new Date().getFullYear() - (y-1)">
            {{ new Date().getFullYear() - (y-1) }}
          </option>
        </select>
      </div>
      <div class="filter-group">
        <label>Month</label>
        <select v-model="filterOptions.month" @change="handleFilterChange">
          <option v-for="m in 12" :key="m" :value="m">
            {{ new Date(2000, m-1, 1).toLocaleString('default', { month: 'long' }) }}
          </option>
        </select>
      </div>
      <div class="filter-group">
        <label>Department</label>
        <select v-model="filterOptions.department" @change="handleFilterChange">
          <option value="">All Departments</option>
          <option v-for="dept in departments" :key="dept.id" :value="dept.id">
            {{ dept.name }}
          </option>
        </select>
      </div>
    </div>

    <div class="charts-container">
      <div v-if="chartData.labels.length" class="chart-box">
        <h3>Daily Trend</h3>
        <Line :data="chartData" :options="chartOptions" />
      </div>
      <div v-if="departmentChartData.labels.length" class="chart-box">
        <h3>Department Comparison</h3>
        <Bar :data="departmentChartData" :options="chartOptions" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.dashboard {
  padding: 20px;
}
.error {
  color: red;
  margin: 10px 0;
}
</style> 