import 'package:geolocator/geolocator.dart';
import 'package:permission_handler/permission_handler.dart';

class LocationService {
  Future<Map<String, double>> getCurrentLocation() async {
    final permission = await Permission.location.request();
    
    if (permission.isDenied) {
      throw Exception('Location permission denied');
    }

    try {
      final position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );

      return {
        'latitude': position.latitude,
        'longitude': position.longitude,
      };
    } catch (e) {
      throw Exception('Failed to get location: $e');
    }
  }
} 