import 'package:flutter_test/flutter_test.dart';
import 'package:http/http.dart' as http;
import 'package:mockito/annotations.dart';
import 'package:mockito/mockito.dart';
import 'package:mobile_flutter/services/api_client.dart';

@GenerateMocks([http.Client])
import 'api_client_test.mocks.dart';

void main() {
  late MockClient mockClient;
  late ApiClient apiClient;

  setUp(() {
    mockClient = MockClient();
    apiClient = ApiClient(mockClient);
  });

  group('ApiClient', () {
    test('login success returns user data', () async {
      when(mockClient.post(
        any,
        headers: anyNamed('headers'),
        body: anyNamed('body'),
      )).thenAnswer((_) async => http.Response(
        '{"token": "test_token", "user": {"id": 1, "name": "Test User"}}',
        200,
      ));

      final result = await apiClient.login('test@example.com', 'password');
      
      expect(result['token'], 'test_token');
      expect(result['user']['name'], 'Test User');
    });

    test('login failure throws ApiException', () async {
      when(mockClient.post(
        any,
        headers: anyNamed('headers'),
        body: anyNamed('body'),
      )).thenAnswer((_) async => http.Response(
        '{"message": "Invalid credentials"}',
        401,
      ));

      expect(
        () => apiClient.login('test@example.com', 'wrong_password'),
        throwsA(isA<ApiException>()),
      );
    });

    group('clockIn', () {
      test('success returns attendance data', () async {
        when(mockClient.post(
          any,
          headers: anyNamed('headers'),
          body: anyNamed('body'),
        )).thenAnswer((_) async => http.Response(
          '{"status": "success", "data": {"time_in": "2024-03-17 09:00:00"}}',
          201,
        ));

        final result = await apiClient.clockIn({'latitude': 0.0, 'longitude': 0.0});
        expect(result['status'], 'success');
      });

      test('network error throws ApiException', () async {
        when(mockClient.post(
          any,
          headers: anyNamed('headers'),
          body: anyNamed('body'),
        )).thenThrow(const http.ClientException('Network error'));

        expect(
          () => apiClient.clockIn({'latitude': 0.0, 'longitude': 0.0}),
          throwsA(isA<ApiException>()),
        );
      });
    });

    // Add similar groups for clockOut and getPayrollSummary
  });
} 