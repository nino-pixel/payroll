import 'package:flutter/material.dart';
import 'package:mobile_flutter/app.dart';
import 'package:mobile_flutter/services/api_client.dart';
import 'package:mobile_flutter/routes/router.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final apiClient = ApiClient();
  await apiClient.init();
  AppRouter.updateAuthStatus(await apiClient.isAuthenticated());
  runApp(const PayrollApp());
}
