import 'dart:io';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/leave_repository.dart';

class LeaveState {
  final bool isLoading;
  final String? error;
  final List<dynamic> requests;

  LeaveState({
    this.isLoading = false,
    this.error,
    this.requests = const [],
  });

  LeaveState copyWith({
    bool? isLoading,
    String? error,
    List<dynamic>? requests,
  }) {
    return LeaveState(
      isLoading: isLoading ?? this.isLoading,
      error: error,
      requests: requests ?? this.requests,
    );
  }
}

class LeaveController extends StateNotifier<LeaveState> {
  final LeaveRepository _repository;

  LeaveController(this._repository) : super(LeaveState());

  Future<void> loadRequests() async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final data = await _repository.getLeaveRequests();
      state = state.copyWith(isLoading: false, requests: data);
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  Future<void> submitRequest({
    required String type,
    required DateTime startDate,
    required DateTime endDate,
    required String reason,
    File? attachment,
    required Function() onSuccess,
  }) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      await _repository.submitLeaveRequest(
        type: type,
        startDate: startDate.toIso8601String().split('T')[0],
        endDate: endDate.toIso8601String().split('T')[0],
        reason: reason,
        attachment: attachment,
      );
      
      await loadRequests();
      onSuccess();
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString().replaceAll('Exception: ', ''));
    }
  }
}

final leaveControllerProvider = StateNotifierProvider<LeaveController, LeaveState>((ref) {
  final repo = ref.watch(leaveRepositoryProvider);
  return LeaveController(repo);
});
