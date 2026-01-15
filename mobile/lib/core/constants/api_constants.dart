class ApiConstants {
  // Replace with your local machine's IP address if testing on real device
  // For Android Emulator, use 10.0.2.2
  // For iOS Simulator, use 127.0.0.1
  static String _baseUrl = 'http://192.168.0.127:8001';
  
  static String get baseUrl => _baseUrl;
  
  static void updateBaseUrl(String url) {
    // Remove trailing slash if present
    if (url.endsWith('/')) {
      _baseUrl = url.substring(0, url.length - 1);
    } else {
      _baseUrl = url;
    }
  }

  static String get apiUrl => '$_baseUrl/api';

  // Auth Constants
  static const String loginEndpoint = '/auth/login';
  static const String logoutEndpoint = '/auth/logout';
  static const String userEndpoint = '/user';
  
  // Attendance Constants
  static const String attendanceTodayEndpoint = '/attendance/today';
  static const String attendanceStoreEndpoint = '/attendance/store';
  static const String attendanceHistoryEndpoint = '/attendance/history';
  static const String attendanceWeeklyEndpoint = '/attendance/weekly-timeline';
  
  // Leave Request Constants
  static const String leaveRequestEndpoint = '/leave-request';
  
  // Storage Keys
  static const String tokenKey = 'auth_token';
  static const String userKey = 'user_data';
}
