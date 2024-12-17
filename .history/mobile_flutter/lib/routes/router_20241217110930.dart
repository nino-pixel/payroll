import 'package:flutter/material.dart';
import 'package:mobile_flutter/screens/auth/login_screen.dart';
import 'package:mobile_flutter/screens/home/home_screen.dart';
import 'package:mobile_flutter/screens/attendance/attendance_screen.dart';
import 'package:mobile_flutter/screens/payroll/payroll_screen.dart';

class AppRouter {
  static Route<dynamic> generateRoute(RouteSettings settings) {
    // TODO: Check auth status and redirect to login if not authenticated
    bool isAuthenticated = false; // This should come from auth service later

    // If not authenticated and not on login screen, redirect to login
    if (!isAuthenticated && settings.name != '/login') {
      return MaterialPageRoute(builder: (_) => const LoginScreen());
    }

    switch (settings.name) {
      case '/':
        return MaterialPageRoute(builder: (_) => const HomeScreen());
      case '/login':
        return MaterialPageRoute(builder: (_) => const LoginScreen());
      case '/attendance':
        return MaterialPageRoute(builder: (_) => const AttendanceScreen());
      case '/payroll':
        return MaterialPageRoute(builder: (_) => const PayrollScreen());
      default:
        return MaterialPageRoute(
          builder: (_) => Scaffold(
            body: Center(
              child: Text('No route defined for ${settings.name}'),
            ),
          ),
        );
    }
  }
} 