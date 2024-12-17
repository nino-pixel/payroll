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

interface PayrollStats {
  total_payroll: number
  total_basic_pay: number
  total_overtime_pay: number
  total_deductions: number
  payroll_count: number
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