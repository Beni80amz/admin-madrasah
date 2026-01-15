import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:hive_flutter/hive_flutter.dart';
import '../../constants/api_constants.dart';
import 'offline_queue_service.dart';

final syncServiceProvider = Provider<SyncService>((ref) {
  final offlineService = ref.watch(offlineQueueServiceProvider);
  return SyncService(offlineService, Dio(), Hive.box('authBox'));
});

class SyncService {
  final OfflineQueueService _queueService;
  final Dio _dio;
  final Box _authBox;

  SyncService(this._queueService, this._dio, this._authBox);

  Future<void> syncData() async {
    final queue = _queueService.getQueue();
    if (queue.isEmpty) return;

    final token = _authBox.get(ApiConstants.tokenKey);
    final options = Options(
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    // Process from oldest to newest? Or reverse? Usually FIFO.
    // getQueue returns list.
    // We should iterate carefully.
    
    // Note: This logic assumes we can remove them one by one. But Hive list index shifting is tricky if we remove while iterating.
    // Better to clear all if successful, or process one by one and delete.
    // Implementing simple one-by-one processing.
    
    // We will read all, try to send all, and remove those that succeeded.
    
    // Actually, Hive box `deleteAt` shifts indices.
    // Best approach: Copy queue, clear box, try send. If fail, add back?
    // Safer: Process head, if success, deleteAt(0). Rinses and repeat.
    
    int processedCount = 0;
    // We get the length first.
    // Wait, getQueue gives a COPY of data.
    // We need to access the box directly via service to delete.
    
    // Simplified: Just try to process current tasks.
    // We'll iterate manually.
    
    // Re-implementation:
    // Read item at 0. Try send. If success, deleteAt(0). If fail, break (stop syncing to preserve order).
    
    // Accessing box length via service would be better. But getQueue returns list.
    // Let's assume we implement a loop.
    
    while (true) {
        final queue = _queueService.getQueue();
        if (queue.isEmpty) break;
        
        final item = queue.first;
        try {
            await _dio.request(
                item['url'],
                data: item['data'],
                options: options.copyWith(method: item['method']),
            );
            // Success
            await _queueService.removeRequest(0);
            processedCount++;
        } catch (e) {
            // Failed to sync this item. Stop syncing.
            print('Sync failed for item: $e');
            break;
        }
    }
    
    if (processedCount > 0) {
        print('Synced $processedCount items.');
    }
  }
}
