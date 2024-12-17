import 'package:flutter/material.dart';
import 'package:mobile_flutter/services/api_client.dart';
import 'package:mobile_flutter/services/location_service.dart';

class AttendanceScreen extends StatefulWidget {
  const AttendanceScreen({super.key});

  @override
  State<AttendanceScreen> createState() => _AttendanceScreenState();
}

class _AttendanceScreenState extends State<AttendanceScreen> {
  bool _isClockedIn = false;
  DateTime? _clockInTime;
  bool _isLoading = false;
  final _apiClient = ApiClient();
  final _locationService = LocationService();
  Map<String, double>? _currentLocation;

  Future<void> _toggleClockIn() async {
    setState(() => _isLoading = true);
    try {
      final location = await _locationService.getCurrentLocation();
      setState(() => _currentLocation = location);

      if (!_isClockedIn) {
        await _apiClient.clockIn(location);
        setState(() {
          _isClockedIn = true;
          _clockInTime = DateTime.now();
        });
      } else {
        await _apiClient.clockOut(location);
        setState(() {
          _isClockedIn = false;
          _clockInTime = null;
        });
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(e.toString()),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                children: [
                  Text(
                    DateTime.now().toString().split(' ')[0],
                    style: Theme.of(context).textTheme.headlineSmall,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    TimeOfDay.now().format(context),
                    style: Theme.of(context).textTheme.headlineMedium,
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          if (_clockInTime != null) ...[
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  children: [
                    const Text('Clocked in at:'),
                    Text(
                      TimeOfDay.fromDateTime(_clockInTime!).format(context),
                      style: Theme.of(context).textTheme.titleLarge,
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
          ],
          if (_currentLocation != null) ...[
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  children: [
                    const Text('Current Location'),
                    Text(
                      'Lat: ${_currentLocation!['latitude']?.toStringAsFixed(6)}\n'
                      'Long: ${_currentLocation!['longitude']?.toStringAsFixed(6)}',
                      style: Theme.of(context).textTheme.bodyLarge,
                      textAlign: TextAlign.center,
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
          ],
          ElevatedButton(
            onPressed: _isLoading ? null : _toggleClockIn,
            style: ElevatedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 16),
              backgroundColor: _isClockedIn ? Colors.red : Colors.green,
            ),
            child: _isLoading
                ? const CircularProgressIndicator()
                : Text(
                    _isClockedIn ? 'Clock Out' : 'Clock In',
                    style: const TextStyle(fontSize: 18),
                  ),
          ),
        ],
      ),
    );
  }
} 