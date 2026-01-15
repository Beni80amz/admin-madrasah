import 'dart:convert';
import 'package:hive_flutter/hive_flutter.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

final offlineQueueServiceProvider = Provider<OfflineQueueService>((ref) {
  return OfflineQueueService(Hive.box('offlineQueue')); // Make sure to open this box in main.dart
});

class OfflineQueueService {
  final Box _queueBox;

  OfflineQueueService(this._queueBox);

  Future<void> addRequest({
    required String url,
    required String method,
    Map<String, dynamic>? data,
  }) async {
    final request = {
      'url': url,
      'method': method,
      'data': data,
      'timestamp': DateTime.now().toIso8601String(),
    };
    await _queueBox.add(request);
  }

  List<Map<String, dynamic>> getQueue() {
    return _queueBox.values.map((e) => Map<String, dynamic>.from(e)).toList();
  }

  Future<void> clearQueue() async {
    await _queueBox.clear();
  }
  
  Future<void> removeRequest(int index) async {
    await _queueBox.deleteAt(index);
  }
}
