import * as XLSX from 'xlsx'

interface PayrollExport {
  Employee: string
  Period: string
  'Basic Pay': string
  'Overtime Pay': string
  Deductions: string
  'Net Pay': string
  Status: string
}

export type ExportFormat = 'excel' | 'csv'

interface PayrollStats {
  total_payroll: number
  total_basic_pay: number
  total_overtime_pay: number
  total_deductions: number
  payroll_count: number
}

const downloadFile = (content: string, filename: string, type: string) => {
  const blob = new Blob([content], { type })
  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = filename
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  window.URL.revokeObjectURL(url)
}

export const exportToCSV = (data: PayrollExport[], stats: PayrollStats, filename: string) => {
  // Prepare summary data
  const summaryRows = [
    ['Payroll Summary'],
    [''],
    ['Total Payroll', formatCurrency(stats.total_payroll)],
    ['Total Basic Pay', formatCurrency(stats.total_basic_pay)],
    ['Total Overtime Pay', formatCurrency(stats.total_overtime_pay)],
    ['Total Deductions', formatCurrency(stats.total_deductions)],
    ['Number of Payrolls', stats.payroll_count.toString()],
    [''],
    ['Detailed Payroll Data'],
    ['']
  ]

  // Convert data to CSV
  const headers = Object.keys(data[0])
  const rows = data.map(obj => Object.values(obj))
  const csvContent = [
    ...summaryRows,
    headers,
    ...rows
  ].map(row => row.join(',')).join('\n')

  downloadFile(csvContent, `${filename}.csv`, 'text/csv')
}

export const exportToExcel = (data: any[], stats: PayrollStats, filename: string) => {
  // Create summary worksheet
  const summaryData = [
    ['Payroll Summary'],
    [''],
    ['Total Payroll', formatCurrency(stats.total_payroll)],
    ['Total Basic Pay', formatCurrency(stats.total_basic_pay)],
    ['Total Overtime Pay', formatCurrency(stats.total_overtime_pay)],
    ['Total Deductions', formatCurrency(stats.total_deductions)],
    ['Number of Payrolls', stats.payroll_count.toString()],
    ['']
  ]

  const summarySheet = XLSX.utils.aoa_to_sheet(summaryData)
  const payrollSheet = XLSX.utils.json_to_sheet(data)
  const workbook = XLSX.utils.book_new()

  XLSX.utils.book_append_sheet(workbook, summarySheet, 'Summary')
  XLSX.utils.book_append_sheet(workbook, payrollSheet, 'Payrolls')
  
  XLSX.writeFile(workbook, `${filename}.xlsx`)
}

export const formatPayrollsForExport = (payrolls: any[]): PayrollExport[] => {
  return payrolls.map(payroll => ({
    Employee: payroll.employee.user.name,
    Period: new Date(payroll.pay_period_start).toLocaleDateString(),
    'Basic Pay': formatCurrency(payroll.basic_pay),
    'Overtime Pay': formatCurrency(payroll.overtime_pay),
    Deductions: formatCurrency(payroll.deductions),
    'Net Pay': formatCurrency(payroll.net_pay),
    Status: payroll.status
  }))
}

const formatCurrency = (amount: number): string => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP'
  }).format(amount)
} 