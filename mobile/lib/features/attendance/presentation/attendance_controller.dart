import 'dart:io';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/attendance_repository.dart';

class AttendanceState {
  final bool isLoading;
  final String? error;
  final Map<String, dynamic>? todayAttendance;
  final List<dynamic> history;
  final List<dynamic> weeklyTimeline;

  AttendanceState({
    this.isLoading = false,
    this.todayAttendance,
    this.history = const [],
    this.weeklyTimeline = const [],
    this.error,
  });

  AttendanceState copyWith({
    bool? isLoading,
    String? error,
    Map<String, dynamic>? todayAttendance,
    List<dynamic>? history,
    List<dynamic>? weeklyTimeline,
  }) {
    return AttendanceState(
      isLoading: isLoading ?? this.isLoading,
      error: error,
      todayAttendance: todayAttendance ?? this.todayAttendance,
      history: history ?? this.history,
      weeklyTimeline: weeklyTimeline ?? this.weeklyTimeline,
    );
  }
}

class AttendanceController extends StateNotifier<AttendanceState> {
  final AttendanceRepository _repository;

  AttendanceController(this._repository) : super(AttendanceState());

  Future<void> loadTodayAttendance() async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final data = await _repository.getTodayAttendance();
      state = state.copyWith(isLoading: false, todayAttendance: data);
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  Future<void> submitAttendance({
    required double latitude,
    required double longitude,
    required String type,
    required String actionStatus,
    String? qrContent,
    File? imageFile,
    required Function() onSuccess,
  }) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      await _repository.submitAttendance(
        latitude: latitude,
        longitude: longitude,
        type: type,
        actionStatus: actionStatus,
        qrContent: qrContent,
        imageFile: imageFile,
      );
      
      // Refresh today's attendance
      await loadTodayAttendance();
      
      onSuccess();
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString().replaceAll('Exception: ', ''));
    }
  }
  
  Future<void> loadHistory({int? month, int? year}) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final data = await _repository.getAttendanceHistory(month: month, year: year);
      state = state.copyWith(isLoading: false, history: data);
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  Future<void> loadWeeklyTimeline() async {
    // state = state.copyWith(isLoading: true, error: null); // Optional: don't show full loading for just timeline
    try {
      final data = await _repository.getWeeklyTimeline();
      state = state.copyWith(weeklyTimeline: data);
    } catch (e) {
      // Fail silently for timeline
      print('Weekly timeline error: $e');
    }
  }
}

final attendanceControllerProvider = StateNotifierProvider<AttendanceController, AttendanceState>((ref) {
  final repo = ref.watch(attendanceRepositoryProvider);
  return AttendanceController(repo);
});
