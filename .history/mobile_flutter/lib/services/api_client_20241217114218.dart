import 'dart:async';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiResponse<T> {
  final T? data;
  final String? error;
  final bool success;

  ApiResponse({this.data, this.error, this.success = true});
}

class ApiClient {
  static const String baseUrl = 'http://10.0.2.2:8000/api';
  final _storage = const FlutterSecureStorage();
  final http.Client _client;
  String? _authToken;

  static final ApiClient _instance = ApiClient._internal(http.Client());
  factory ApiClient([http.Client? client]) => 
    client != null ? ApiClient._internal(client) : _instance;
  ApiClient._internal(this._client);

  Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    if (_authToken != null) 'Authorization': 'Bearer $_authToken',
  };

  Future<ApiResponse<Map<String, dynamic>>> login(String email, String password) async {
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
      
      if (response.statusCode == 200 && data['token'] != null) {
        await setAuthToken(data['token']);
        return ApiResponse(data: data);
      } else {
        return ApiResponse(
          success: false,
          error: data['message'] ?? 'Login failed',
        );
      }
    } on http.ClientException {
      return ApiResponse(
        success: false,
        error: 'Network error. Please check your connection.',
      );
    } catch (e) {
      return ApiResponse(
        success: false,
        error: 'An unexpected error occurred',
      );
    }
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
    _authToken = await _storage.read(key: 'auth_token');
    return _authToken != null;
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

  Future<ApiResponse<Map<String, dynamic>>> register({
    required String name,
    required String email,
    required String password,
  }) async {
    try {
      final response = await _client.post(
        Uri.parse('$baseUrl/register'),
        headers: _headers,
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'role_id': 4, // Default to employee role
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 201 && data['token'] != null) {
        await setAuthToken(data['token']);
        return ApiResponse(data: data);
      } else {
        return ApiResponse(
          success: false,
          error: data['message'] ?? 'Registration failed',
        );
      }
    } on http.ClientException {
      return ApiResponse(
        success: false,
        error: 'Network error. Please check your connection.',
      );
    } catch (e) {
      return ApiResponse(
        success: false,
        error: 'An unexpected error occurred',
      );
    }
  }
} 