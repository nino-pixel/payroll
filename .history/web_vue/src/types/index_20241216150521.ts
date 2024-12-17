export interface PayrollAnalytics {
  period: {
    start_date: string
    end_date: string
  }
  anomalies: {
    anomalies: Array<{
      id: number
      employee_id: number
      net_pay: number
      // Add other relevant fields
    }>
    statistics: {
      mean: number
      std_dev: number
      threshold: number
      total_analyzed: number
      anomalies_found: number
    }
  }
  time_series: {
    moving_averages: Array<{
      date: string
      value: number
    }>
    trend: Array<{
      date: string
      value: number
    }>
    metadata: {
      window_size: number
      data_points: number
    }
  }
  seasonal_patterns: {
    seasonal_indices: Record<string, number>
    yearly_average: number
    metadata: {
      months_analyzed: number
    }
  }
  metadata: {
    total_records: number
    departments: string[]
  }
} 