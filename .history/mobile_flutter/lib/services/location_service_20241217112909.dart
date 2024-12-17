import 'dart:async';
import 'package:geolocator/geolocator.dart';
import 'package:permission_handler/permission_handler.dart';

class LocationException implements Exception {
  final String message;
  LocationException(this.message);
  
  @override
  String toString() => message;
}

class LocationService {
  Future<bool> checkLocationEnabled() async {
    return await Geolocator.isLocationServiceEnabled();
  }

  Future<Map<String, double>> getCurrentLocation() async {
    // First check if location services are enabled
    if (!await checkLocationEnabled()) {
      throw LocationException('Location services are disabled. Please enable them in settings.');
    }

    // Then check/request permission
    final permission = await Permission.location.request();
    if (permission.isDenied) {
      throw LocationException('Location permission is required for attendance');
    }
    
    if (permission.isPermanentlyDenied) {
      throw LocationException('Location permission is permanently denied. Please enable it in app settings.');
    }

    try {
      final position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
        timeLimit: const Duration(seconds: 5),
      );

      return {
        'latitude': position.latitude,
        'longitude': position.longitude,
      };
    } on TimeoutException {
      throw LocationException('Location request timed out. Please try again.');
    } catch (e) {
      throw LocationException('Failed to get location: $e');
    }
  }
} 