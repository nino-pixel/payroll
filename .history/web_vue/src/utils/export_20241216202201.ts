import * as XLSX from 'xlsx'
import { parse } from 'papaparse'

export interface ExportError extends Error {
  type: 'excel' | 'csv'
  details?: string
}

export interface PayrollExport {
  Employee: string
  Period: string
  'Basic Pay': string
  'Overtime Pay': string
  Deductions: string
  'Net Pay': string
  Status: string
}

export type ExportFormat = 'excel' | 'csv'

export interface PayrollStats {
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
  try {
    // Prepare summary data
    const summaryRows = [
      ['Payroll Summary'],
      [''],
      ['Total Payroll', formatCurrency(stats.total_payroll)],
      ['Total Basic Pay', formatCurrency(stats.total_basic_pay)],
      ['Total Overtime Pay', formatCurrency(stats.total_overtime_pay)],
      ['Total Deductions', formatCurrency(stats.total_deductions)],
      ['Number of Payrolls', stats.payroll_count.toString()],
      ['Average Net Pay', formatCurrency(stats.total_payroll / stats.payroll_count)],
      [''],
      ['Detailed Payroll Data'],
      ['']
    ]

    // Convert data to CSV using papaparse for better handling
    const csvData = [
      ...summaryRows,
      Object.keys(data[0]),
      ...data.map(obj => Object.values(obj))
    ]

    const csvContent = parse(csvData, {
      quotes: true,  // Add quotes around fields
      escapeFormulae: true,  // Prevent formula injection
      newline: '\n',
      skipEmptyLines: false
    }).data.map(row => row.join(',')).join('\n')

    downloadFile(csvContent, `${filename}.csv`, 'text/csv')
  } catch (err) {
    const error = new Error('Failed to generate CSV file') as ExportError
    error.type = 'csv'
    error.details = err instanceof Error ? err.message : 'Unknown error'
    throw error
  }
}

export const exportToExcel = (data: any[], stats: PayrollStats, filename: string) => {
  try {
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
  } catch (err) {
    const error = new Error('Failed to generate Excel file') as ExportError
    error.type = 'excel'
    error.details = err instanceof Error ? err.message : 'Unknown error'
    throw error
  }
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