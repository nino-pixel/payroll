import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

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
  
  final http.Client _client;
  String? _authToken;
  final _storage = const FlutterSecureStorage();

  // Singleton pattern with optional client injection for testing
  static final ApiClient _instance = ApiClient._internal(http.Client());
  factory ApiClient([http.Client? client]) => 
    client != null ? ApiClient._internal(client) : _instance;
  ApiClient._internal(this._client);

  Future<void> init() async {
    // Load saved token on app start
    _authToken = await _storage.read(key: 'auth_token');
  }

  Future<void> setAuthToken(String token) async {
    _authToken = token;
    await _storage.write(key: 'auth_token', value: token);
  }

  Future<void> clearAuthToken() async {
    _authToken = null;
    await _storage.delete(key: 'auth_token');
  }

  Future<bool> isAuthenticated() async {
    return await _storage.read(key: 'auth_token') != null;
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
        await setAuthToken(data['token']);
        return data;
      } else {
        throw ApiException(
          data['message'] ?? 'Login failed',
          response.statusCode
        );
      }
    } catch (e) {
      throw ApiException('Failed to connect to server');
    }
  }

  Future<Map<String, dynamic>> clockIn(Map<String, dynamic> location) async {
    try {
      final response = await _client.post(
        Uri.parse('$baseUrl/attendance/clock-in'),
        headers: _headers,
        body: jsonEncode({
          'location_in': location,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 201) {
        return data;
      } else {
        throw ApiException(
          data['message'] ?? 'Failed to clock in',
          response.statusCode
        );
      }
    } on http.ClientException {
      throw ApiException('Network error. Please check your connection.');
    } catch (e) {
      throw ApiException('An unexpected error occurred');
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

  Future<void> logout() async {
    try {
      await _client.post(
        Uri.parse('$baseUrl/logout'),
        headers: _headers,
      );
    } finally {
      await clearAuthToken();
    }
  }
} 