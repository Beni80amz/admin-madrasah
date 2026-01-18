import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:hive_flutter/hive_flutter.dart';
import '../../../core/constants/api_constants.dart';

final authRepositoryProvider = Provider<AuthRepository>((ref) {
  return AuthRepository(Dio(), Hive.box('authBox'));
});

class AuthRepository {
  final Dio _dio;
  final Box _authBox;

  AuthRepository(this._dio, this._authBox);

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _dio.post(
        '${ApiConstants.apiUrl}${ApiConstants.loginEndpoint}',
        data: {
          'email': email,
          'password': password,
          'device_name': 'mobile_app', // You might want to get real device name later
        },
        options: Options(
          headers: {
            'Accept': 'application/json',
          },
        ),
      );

      final data = response.data;
      if (data['status'] == 'success') {
        final token = data['data']['token'];
        final user = data['data']['user'];
        final userType = data['data']['user_type'];
        final profile = data['data']['profile'];
        final schoolProfile = data['data']['school_profile'];

        // Save to Hive
        await _authBox.put(ApiConstants.tokenKey, token);
        await _authBox.put(ApiConstants.userKey, {
          'user': user,
          'user_type': userType,
          'profile': profile,
          'school_profile': schoolProfile,
        });
        
        return data['data'];
      } else {
        throw Exception(data['message'] ?? 'Login failed');
      }
    } on DioException catch (e) {
      if (e.response != null) {
        throw Exception(e.response?.data['message'] ?? 'Login failed');
      } else {
        throw Exception('Network error: ${e.message}');
      }
    } catch (e) {
      throw Exception('An error occurred: $e');
    }
  }

  Future<void> logout() async {
    final token = _authBox.get(ApiConstants.tokenKey);
    if (token != null) {
      try {
        await _dio.post(
          '${ApiConstants.apiUrl}${ApiConstants.logoutEndpoint}',
          options: Options(
            headers: {
              'Authorization': 'Bearer $token',
              'Accept': 'application/json',
            },
          ),
        );
      } catch (e) {
        // Limit error handling here, just clear local state
        print('Logout error: $e');
      }
    }
    await _authBox.clear();
  }

  String? getToken() {
    return _authBox.get(ApiConstants.tokenKey);
  }

  Map<String, dynamic>? getUserData() {
    final data = _authBox.get(ApiConstants.userKey);
    if (data != null) {
      // Hive stores Map<dynamic, dynamic>, ensuring String keys
      return Map<String, dynamic>.from(data);
    }
    return null;
  }
  
  bool get isAuthenticated => getToken() != null;

  Future<Map<String, dynamic>?> getSchoolProfile() async {
    try {
      final response = await _dio.get(
        '${ApiConstants.apiUrl}/school-profile',
        options: Options(
          headers: {
            'Accept': 'application/json',
          },
        ),
      );

      if (response.statusCode == 200 && response.data['status'] == 'success') {
        return response.data['data'];
      }
      return null;
    } catch (e) {
      print('Failed to load school profile: $e');
      return null;
    }
  }
}
