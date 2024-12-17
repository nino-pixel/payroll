import 'package:flutter/material.dart';

class AttendanceScreen extends StatefulWidget {
  const AttendanceScreen({super.key});

  @override
  State<AttendanceScreen> createState() => _AttendanceScreenState();
}

class _AttendanceScreenState extends State<AttendanceScreen> {
  bool _isClockedIn = false;
  DateTime? _clockInTime;

  void _toggleClockIn() {
    setState(() {
      _isClockedIn = !_isClockedIn;
      if (_isClockedIn) {
        _clockInTime = DateTime.now();
      } else {
        _clockInTime = null;
      }
    });
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
          ElevatedButton(
            onPressed: _toggleClockIn,
            style: ElevatedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 16),
              backgroundColor: _isClockedIn ? Colors.red : Colors.green,
            ),
            child: Text(
              _isClockedIn ? 'Clock Out' : 'Clock In',
              style: const TextStyle(fontSize: 18),
            ),
          ),
        ],
      ),
    );
  }
} 