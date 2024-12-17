import 'package:flutter/material.dart';
import 'package:mobile_flutter/app.dart';
import 'package:mobile_flutter/lib/services/api_client.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final apiClient = ApiClient();
  await apiClient.init();
  runApp(const PayrollApp());
}
