import 'dart:convert';
import 'dart:io';
import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:hive_flutter/hive_flutter.dart';
import '../../../core/constants/api_constants.dart';

final leaveRepositoryProvider = Provider<LeaveRepository>((ref) {
  return LeaveRepository(Dio(), Hive.box('authBox'));
});

class LeaveRepository {
  final Dio _dio;
  final Box _authBox;

  LeaveRepository(this._dio, this._authBox);

  String? get _token => _authBox.get(ApiConstants.tokenKey);

  Options get _options => Options(
        headers: {
          'Authorization': 'Bearer $_token',
          'Accept': 'application/json',
        },
      );

  Future<List<dynamic>> getLeaveRequests() async {
    try {
      final response = await _dio.get(
        '${ApiConstants.apiUrl}${ApiConstants.leaveRequestEndpoint}',
        options: _options,
      );

      if (response.data['status'] == 'success') {
        return response.data['data']['leave_requests'];
      }
      return [];
    } catch (e) {
      throw Exception('Failed to fetch leave requests: $e');
    }
  }

  Future<void> submitLeaveRequest({
    required String type, // 'sakit', 'izin'
    required String startDate,
    required String endDate,
    required String reason,
    File? attachment,
  }) async {
    try {
      String? base64Image;
      if (attachment != null) {
        final bytes = await attachment.readAsBytes();
        base64Image = base64Encode(bytes);
      }

      final data = {
        'type': type,
        'start_date': startDate,
        'end_date': endDate,
        'reason': reason,
      };

      if (base64Image != null) {
        data['attachment_base64'] = base64Image;
      }

      final response = await _dio.post(
        '${ApiConstants.apiUrl}${ApiConstants.leaveRequestEndpoint}',
        data: data,
        options: _options,
      );

      if (response.data['status'] != 'success') {
         throw Exception(response.data['message']);
      }
    } on DioException catch (e) {
       if (e.response != null) {
        throw Exception(e.response?.data['message'] ?? 'Submission failed');
      }
      throw Exception('Network error: ${e.message}');
    } catch (e) {
      throw Exception('Error submitting leave request: $e');
    }
  }
}
