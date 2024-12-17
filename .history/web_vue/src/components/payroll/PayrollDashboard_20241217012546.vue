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

<style scoped>
.dashboard {
  padding: 20px;
}
.error {
  color: red;
  margin: 10px 0;
}
</style> 