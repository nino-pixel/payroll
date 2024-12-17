import 'package:flutter/material.dart';
import 'package:mobile_flutter/config/theme.dart';
import 'package:mobile_flutter/routes/router.dart';

class PayrollApp extends StatelessWidget {
  const PayrollApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Payroll & Attendance',
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      initialRoute: '/',
      onGenerateRoute: AppRouter.generateRoute,
    );
  }
} 