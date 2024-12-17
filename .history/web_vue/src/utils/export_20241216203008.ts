import * as XLSX from 'xlsx'
import { unparse } from 'papaparse'

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

    const csvContent = unparse(csvData, {
      quotes: true,  // Add quotes around fields
      escapeFormulae: true,  // Prevent formula injection
      newline: '\n',
      skipEmptyLines: false
    })

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
    // Styles configuration
    const styles = {
      header: {
        font: { bold: true, color: { rgb: "FFFFFF" } },
        fill: { fgColor: { rgb: "4F46E5" } },
        alignment: { horizontal: 'center', vertical: 'center' },
        border: {
          top: { style: 'thin' },
          bottom: { style: 'thin' },
          left: { style: 'thin' },
          right: { style: 'thin' }
        }
      },
      number: {
        numFmt: '#,##0.00',
        alignment: { horizontal: 'right' }
      },
      summaryHeader: {
        font: { bold: true, size: 14 },
        fill: { fgColor: { rgb: "E0E7FF" } },
        alignment: { horizontal: 'center' }
      },
      summaryRow: {
        font: { bold: true },
        border: {
          bottom: { style: 'thin' }
        }
      }
    }

    // Create summary worksheet with enhanced formatting
    const summaryData = [
      ['Payroll Summary'],
      [''],
      ['Total Payroll', formatCurrency(stats.total_payroll)],
      ['Total Basic Pay', formatCurrency(stats.total_basic_pay)],
      ['Total Overtime Pay', formatCurrency(stats.total_overtime_pay)],
      ['Total Deductions', formatCurrency(stats.total_deductions)],
      ['Number of Payrolls', stats.payroll_count.toString()],
      ['Average Net Pay', formatCurrency(stats.total_payroll / stats.payroll_count)],
      ['Average Basic Pay', formatCurrency(stats.total_basic_pay / stats.payroll_count)],
      ['Average Overtime Pay', formatCurrency(stats.total_overtime_pay / stats.payroll_count)],
      ['']
    ]

    const summarySheet = XLSX.utils.aoa_to_sheet(summaryData)
    const payrollSheet = XLSX.utils.json_to_sheet(data)

    // Apply column widths
    const colWidths = [
      { wch: 30 }, // Employee
      { wch: 15 }, // Period
      { wch: 15 }, // Basic Pay
      { wch: 15 }, // Overtime Pay
      { wch: 15 }, // Deductions
      { wch: 15 }, // Net Pay
      { wch: 12 }  // Status
    ]

    payrollSheet['!cols'] = colWidths
    summarySheet['!cols'] = [{ wch: 25 }, { wch: 20 }]

    // Apply styles to payroll sheet
    const range = XLSX.utils.decode_range(payrollSheet['!ref'] || 'A1:G1')

    // Headers
    for (let C = range.s.c; C <= range.e.c; ++C) {
      const addr = XLSX.utils.encode_cell({ r: range.s.r, c: C })
      if (!payrollSheet[addr]) continue
      payrollSheet[addr].s = styles.header
    }

    // Apply number formatting to amount columns (C to F)
    for (let R = range.s.r + 1; R <= range.e.r; ++R) {
      for (let C = 2; C <= 5; ++C) {
        const addr = XLSX.utils.encode_cell({ r: R, c: C })
        if (!payrollSheet[addr]) continue
        payrollSheet[addr].s = styles.number
      }
    }

    // Apply conditional formatting for status column
    for (let R = range.s.r + 1; R <= range.e.r; ++R) {
      const addr = XLSX.utils.encode_cell({ r: R, c: 6 })
      if (!payrollSheet[addr]) continue
      const status = payrollSheet[addr].v
      payrollSheet[addr].s = {
        font: { bold: true },
        fill: {
          fgColor: { 
            rgb: status === 'approved' ? '86EFAC' :  // green
                 status === 'pending' ? 'FEF08A' :   // yellow
                 'FCA5A5'                            // red
          }
        },
        alignment: { horizontal: 'center' }
      }
    }

    // Apply summary sheet styling
    summarySheet['A1'].s = styles.summaryHeader
    for (let R = 2; R <= 10; R++) {
      const addr1 = XLSX.utils.encode_cell({ r: R, c: 0 })
      const addr2 = XLSX.utils.encode_cell({ r: R, c: 1 })
      if (summarySheet[addr1]) summarySheet[addr1].s = styles.summaryRow
      if (summarySheet[addr2]) summarySheet[addr2].s = styles.number
    }

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