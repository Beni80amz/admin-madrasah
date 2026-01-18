import 'dart:convert';
import 'dart:io';
import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:hive_flutter/hive_flutter.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/network/network_info.dart';
import '../../../core/offline/offline_queue_service.dart';

import '../../../core/services/device_id_service.dart';

final attendanceRepositoryProvider = Provider<AttendanceRepository>((ref) {
  final networkInfo = ref.watch(networkInfoProvider);
  final offlineQueue = ref.watch(offlineQueueServiceProvider);
  final deviceIdService = ref.watch(deviceIdServiceProvider);
  return AttendanceRepository(Dio(), Hive.box('authBox'), networkInfo, offlineQueue, deviceIdService);
});

class AttendanceRepository {
  final Dio _dio;
  final Box _authBox;
  final NetworkInfo _networkInfo;
  final OfflineQueueService _offlineQueue;
  final DeviceIdService _deviceIdService;

  AttendanceRepository(this._dio, this._authBox, this._networkInfo, this._offlineQueue, this._deviceIdService);

  String? get _token => _authBox.get(ApiConstants.tokenKey);

  Options get _options => Options(
        headers: {
          'Authorization': 'Bearer $_token',
          'Accept': 'application/json',
        },
      );

  Future<Map<String, dynamic>?> getTodayAttendance() async {
    try {
      final response = await _dio.get(
        '${ApiConstants.apiUrl}${ApiConstants.attendanceTodayEndpoint}',
        options: _options,
      );

      if (response.statusCode == 200 && response.data['status'] == 'success') {
        return response.data['data']['attendance'];
      }
      return null;
    } catch (e) {
      throw Exception('Failed to get attendance status: $e');
    }
  }

  Future<void> submitAttendance({
    required double latitude,
    required double longitude,
    required String type, // 'qr' or 'selfie'
    required String actionStatus, // 'masuk' or 'pulang'
    String? qrContent,
    File? imageFile,
    bool forceOffline = false,
  }) async {
    final isConnected = await _networkInfo.isConnected;
    
    // Prepare Data
      String? base64Image;
      if (imageFile != null) {
        final bytes = await imageFile.readAsBytes();
        base64Image = base64Encode(bytes);
      }

      final Map<String, dynamic> data = {
        'latitude': latitude,
        'longitude': longitude,
        'type': type,
        'action_status': actionStatus,
        'action_status': actionStatus,
        'device_id': _deviceIdService.getDeviceId(), // Send real persistent ID
      };

      if (type == 'qr' && qrContent != null) {
        data['qr_content'] = qrContent;
      }

      if ((type == 'selfie' || imageFile != null) && base64Image != null) {
        data['image'] = base64Image;
      }
      
    if (!isConnected || forceOffline) {
       // Queue logic
       await _offlineQueue.addRequest(
          url: '${ApiConstants.apiUrl}${ApiConstants.attendanceStoreEndpoint}',
          method: 'POST',
          data: data,
       );
       return; 
    }

    // Online logic
    try {
      final response = await _dio.post(
        '${ApiConstants.apiUrl}${ApiConstants.attendanceStoreEndpoint}',
        data: data,
        options: _options,
      );

      if (response.data['status'] == 'success') {
        return; // Success
      } else {
        throw Exception(response.data['message']);
      }
    } on DioException catch (e) {
       if (e.type == DioExceptionType.connectionError || e.type == DioExceptionType.connectionTimeout) {
           // Fallback to queue if timeout
           await _offlineQueue.addRequest(
              url: '${ApiConstants.apiUrl}${ApiConstants.attendanceStoreEndpoint}',
              method: 'POST',
              data: data,
           );
           return;
       }
       
       if (e.response != null) {
        throw Exception(e.response?.data['message'] ?? 'Submission failed');
      }
      throw Exception('Network error: ${e.message}');
    } catch (e) {
      throw Exception('Error submitting attendance: $e');
    }
  }

  Future<List<dynamic>> getAttendanceHistory({int? month, int? year}) async {
    try {
      final response = await _dio.get(
        '${ApiConstants.apiUrl}${ApiConstants.attendanceHistoryEndpoint}',
        queryParameters: {
          if (month != null) 'month': month,
          if (year != null) 'year': year,
        },
        options: _options,
      );
      
      if (response.data['status'] == 'success') {
        // Backend returns { data: { attendances: [], summary: {} } }
        final data = response.data['data'];
        if (data is Map && data.containsKey('attendances')) {
           return data['attendances'] as List<dynamic>;
        }
        // Fallback or if structure allows direct list
        return data is List ? data : [];
      } else {
        throw Exception(response.data['message'] ?? 'Failed to load history');
      }
    } catch (e) {
      throw Exception('Failed to load history: $e');
    }
  }

  Future<List<dynamic>> getWeeklyTimeline() async {
    try {
      final response = await _dio.get(
        '${ApiConstants.apiUrl}${ApiConstants.attendanceWeeklyEndpoint}',
        options: _options,
      );
      
      if (response.data['status'] == 'success') {
         // Backend returns { data: { timeline: { "Sen": {...}, ... } } }
         final data = response.data['data'];
         
         if (data is Map && data.containsKey('timeline')) {
            final timeline = data['timeline'];
            if (timeline is Map) {
              return timeline.values.toList();
            } else if (timeline is List) {
              return timeline;
            }
         }
         
         if (data is List) return data;
         return [];
      } else {
        throw Exception(response.data['message'] ?? 'Failed to load weekly timeline');
      }
    } catch (e) {
      throw Exception('Failed to load weekly timeline: $e');
    }
  }
}
