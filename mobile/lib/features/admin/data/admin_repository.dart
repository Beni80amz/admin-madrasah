import '../../auth/data/auth_repository.dart';

// ...

  Options get _options {
    final token = _ref.read(authRepositoryProvider).getToken();
    return Options(headers: {
      'Authorization': 'Bearer $token',
      'Accept': 'application/json',
    });
  }

  Future<List<dynamic>> searchUsers(String query) async {
    try {
      final response = await _dio.get(
        '${ApiConstants.apiUrl}/admin/users',
        queryParameters: {'search': query},
        options: _options,
      );

      if (response.data['status'] == 'success') {
        return response.data['data']['data']; // Laravel paginate structure
      } else {
        throw Exception(response.data['message']);
      }
    } catch (e) {
      throw Exception('Gagal mencari user: $e');
    }
  }

  Future<void> resetDevice(int userId) async {
    try {
      final response = await _dio.post(
        '${ApiConstants.apiUrl}/admin/users/$userId/reset-device',
        options: _options,
      );

      if (response.data['status'] != 'success') {
         throw Exception(response.data['message']);
      }
    } catch (e) {
      throw Exception('Gagal reset device: $e');
    }
  }
}
