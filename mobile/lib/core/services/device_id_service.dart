import 'dart:math';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:hive_flutter/hive_flutter.dart';

final deviceIdServiceProvider = Provider<DeviceIdService>((ref) {
  return DeviceIdService(Hive.box('authBox'));
});

class DeviceIdService {
  final Box _box;
  static const String _deviceIdKey = 'persistent_device_id';

  DeviceIdService(this._box);

  String getDeviceId() {
    String? deviceId = _box.get(_deviceIdKey);
    
    if (deviceId == null) {
      deviceId = _generateUniqueId();
      _box.put(_deviceIdKey, deviceId);
    }
    
    return deviceId;
  }

  String _generateUniqueId() {
    // Generate a UUID-like string
    // Format: "android_app_[random_string]" or just UUID
    // We use a simple random string generator for simplicity and uniqueness
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    final rnd = Random.secure();
    final buffer = StringBuffer();
    
    // Time component to ensure uniqueness over time
    buffer.write(DateTime.now().millisecondsSinceEpoch.toRadixString(16));
    buffer.write('_');
    
    // Random component
    for (int i = 0; i < 16; i++) {
      buffer.write(chars[rnd.nextInt(chars.length)]);
    }
    
    return buffer.toString();
  }
}
