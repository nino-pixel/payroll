import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiException implements Exception {
  final String message;
  final int? statusCode;

  ApiException(this.message, [this.statusCode]);

  @override
  String toString() => message;
}

class ApiClient {
  static const String baseUrl = 'http://10.0.2.2:8000/api'; // For Android emulator
  // static const String baseUrl = 'http://localhost:8000/api'; // For iOS simulator
  
  final http.Client _client = http.Client();
  String? _authToken;

  // Singleton pattern
  static final ApiClient _instance = ApiClient._internal();
  factory ApiClient() => _instance;
  ApiClient._internal();

  void setAuthToken(String token) {
    _authToken = token;
  }

  Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    if (_authToken != null) 'Authorization': 'Bearer $_authToken',
  };

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _client.post(
        Uri.parse('$baseUrl/login'),
        headers: _headers,
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        setAuthToken(data['token']);
        return data;
      } else {
        throw ApiException(
          data['message'] ?? 'Login failed',
          response.statusCode
        );
      }
    } on http.ClientException {
      throw ApiException('Network error. Please check your connection.');
    } catch (e) {
      throw ApiException('An unexpected error occurred');
    }
  }

  Future<Map<String, dynamic>> clockIn(Map<String, dynamic> location) async {
    final response = await _client.post(
      Uri.parse('$baseUrl/attendance/clock-in'),
      headers: _headers,
      body: jsonEncode({
        'location_in': location,
      }),
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to clock in: ${response.body}');
    }
  }

  Future<Map<String, dynamic>> clockOut(Map<String, dynamic> location) async {
    final response = await _client.post(
      Uri.parse('$baseUrl/attendance/clock-out'),
      headers: _headers,
      body: jsonEncode({
        'location_out': location,
      }),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to clock out: ${response.body}');
    }
  }

  Future<Map<String, dynamic>> getPayrollSummary() async {
    final response = await _client.get(
      Uri.parse('$baseUrl/payroll/summary'),
      headers: _headers,
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to get payroll summary: ${response.body}');
    }
  }
} 