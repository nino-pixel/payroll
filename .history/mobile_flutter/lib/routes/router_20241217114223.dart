import 'package:flutter/material.dart';
import 'package:mobile_flutter/screens/auth/login_screen.dart';
import 'package:mobile_flutter/screens/home/home_screen.dart';
import 'package:mobile_flutter/screens/attendance/attendance_screen.dart';
import 'package:mobile_flutter/screens/payroll/payroll_screen.dart';
import 'package:mobile_flutter/screens/auth/register_screen.dart';
import 'package:mobile_flutter/services/api_client.dart';

class AppRouter {
  static final _apiClient = ApiClient();
  static bool _isAuthenticated = false;

  static void updateAuthStatus(bool status) {
    _isAuthenticated = status;
  }

  static Route<dynamic> generateRoute(RouteSettings settings) {
    if (!_isAuthenticated && settings.name != '/login') {
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
      case '/register':
        return MaterialPageRoute(builder: (_) => const RegisterScreen());
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