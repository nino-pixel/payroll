<?php

namespace App\Services;

use App\Models\Payroll;
use Illuminate\Support\Collection;

class PayrollAnalytics
{
    /**
     * Detect anomalies in payroll data using Z-score method
     */
    public function detectAnomalies(Collection $payrolls, float $threshold = 2.0): array
    {
        $netPays = $payrolls->pluck('net_pay')->toArray();
        $mean = array_sum($netPays) / count($netPays);
        
        // Calculate standard deviation
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $netPays)) / count($netPays);
        $stdDev = sqrt($variance);

        // Detect anomalies using Z-score
        $anomalies = $payrolls->filter(function($payroll) use ($mean, $stdDev, $threshold) {
            $zScore = abs(($payroll->net_pay - $mean) / $stdDev);
            return $zScore > $threshold;
        });

        return [
            'anomalies' => $anomalies,
            'statistics' => [
                'mean' => $mean,
                'std_dev' => $stdDev,
                'threshold' => $threshold,
                'total_analyzed' => count($netPays),
                'anomalies_found' => $anomalies->count()
            ]
        ];
    }

    /**
     * Perform time series analysis using moving averages
     */
    public function timeSeriesAnalysis(Collection $payrolls, int $windowSize = 3): array
    {
        $sortedPayrolls = $payrolls->sortBy('pay_period_start');
        $values = $sortedPayrolls->pluck('net_pay')->toArray();
        $dates = $sortedPayrolls->pluck('pay_period_start')->toArray();

        // Calculate moving average
        $movingAverages = [];
        for ($i = $windowSize - 1; $i < count($values); $i++) {
            $sum = 0;
            for ($j = 0; $j < $windowSize; $j++) {
                $sum += $values[$i - $j];
            }
            $movingAverages[] = [
                'date' => $dates[$i],
                'value' => $sum / $windowSize
            ];
        }

        // Calculate trend
        $trend = [];
        if (count($values) >= 2) {
            $x = range(0, count($values) - 1);
            $y = $values;
            
            $n = count($x);
            $sumX = array_sum($x);
            $sumY = array_sum($y);
            $sumXY = array_sum(array_map(function($x, $y) { return $x * $y; }, $x, $y));
            $sumX2 = array_sum(array_map(function($x) { return $x * $x; }, $x));
            
            $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
            $intercept = ($sumY - $slope * $sumX) / $n;

            foreach ($x as $i) {
                $trend[] = [
                    'date' => $dates[$i],
                    'value' => $intercept + $slope * $i
                ];
            }
        }

        return [
            'moving_averages' => $movingAverages,
            'trend' => $trend,
            'metadata' => [
                'window_size' => $windowSize,
                'data_points' => count($values)
            ]
        ];
    }

    /**
     * Generate seasonal decomposition of payroll data
     */
    public function seasonalDecomposition(Collection $payrolls): array
    {
        $monthlyData = $payrolls->groupBy(function($payroll) {
            return $payroll->pay_period_start->format('m');
        })->map(function($group) {
            return $group->avg('net_pay');
        });

        // Calculate seasonal indices
        $yearlyAverage = $monthlyData->avg();
        $seasonalIndices = $monthlyData->map(function($value) use ($yearlyAverage) {
            return $value / $yearlyAverage;
        });

        return [
            'seasonal_indices' => $seasonalIndices,
            'yearly_average' => $yearlyAverage,
            'metadata' => [
                'months_analyzed' => $monthlyData->count()
            ]
        ];
    }
} 