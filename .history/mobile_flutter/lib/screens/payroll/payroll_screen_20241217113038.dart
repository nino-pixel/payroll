import 'package:flutter/material.dart';
import 'package:mobile_flutter/services/api_client.dart';

class PayrollScreen extends StatefulWidget {
  const PayrollScreen({super.key});

  @override
  State<PayrollScreen> createState() => _PayrollScreenState();
}

class _PayrollScreenState extends State<PayrollScreen> {
  Map<String, dynamic>? _payrollData;
  bool _isLoading = false;
  final _apiClient = ApiClient();

  @override
  void initState() {
    super.initState();
    _fetchPayrollData();
  }

  Future<void> _fetchPayrollData() async {
    setState(() => _isLoading = true);
    try {
      final data = await _apiClient.getPayrollSummary();
      setState(() => _payrollData = data);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(e.toString()),
            backgroundColor: Colors.red,
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: _fetchPayrollData,
      child: SingleChildScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            if (_isLoading)
              const Center(
                child: Padding(
                  padding: EdgeInsets.all(16.0),
                  child: CircularProgressIndicator(),
                ),
              ),
            if (_payrollData != null) ...[
              _buildPayPeriodCard(),
              const SizedBox(height: 16),
              _buildEarningsCard(),
              const SizedBox(height: 16),
              _buildDeductionsCard(),
            ],
            if (_payrollData == null && !_isLoading)
              const Center(
                child: Text('No payroll data available'),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildPayPeriodCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Pay Period',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 8),
            Text(
              _payrollData?['pay_period'] ?? 'Current Period',
              style: Theme.of(context).textTheme.bodyLarge,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEarningsCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Earnings',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 16),
            _buildPayrollRow('Basic Pay', _formatCurrency(_payrollData?['basic_pay'] ?? 0)),
            const SizedBox(height: 8),
            _buildPayrollRow('Overtime', _formatCurrency(_payrollData?['overtime_pay'] ?? 0)),
            const SizedBox(height: 8),
            _buildPayrollRow('Allowances', _formatCurrency(_payrollData?['allowances'] ?? 0)),
            const Divider(height: 32),
            _buildPayrollRow(
              'Total Earnings',
              _formatCurrency(_payrollData?['total_earnings'] ?? 0),
              bold: true,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDeductionsCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Deductions',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 16),
            _buildPayrollRow('Tax', _formatCurrency(_payrollData?['tax'] ?? 0)),
            const SizedBox(height: 8),
            _buildPayrollRow('Insurance', _formatCurrency(_payrollData?['insurance'] ?? 0)),
            const Divider(height: 32),
            _buildPayrollRow(
              'Net Pay',
              _formatCurrency(_payrollData?['net_pay'] ?? 0),
              bold: true,
              color: Colors.green,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPayrollRow(String label, String value, {bool bold = false, Color? color}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(label),
        Text(
          value,
          style: TextStyle(
            fontWeight: bold ? FontWeight.bold : null,
            color: color,
          ),
        ),
      ],
    );
  }

  String _formatCurrency(num amount) {
    return '₱${amount.toStringAsFixed(2)}';
  }
} 