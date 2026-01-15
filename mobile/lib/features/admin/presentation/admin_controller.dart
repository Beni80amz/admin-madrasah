import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/admin_repository.dart';

class AdminState {
  final bool isLoading;
  final List<dynamic> users;
  final String? error;

  AdminState({this.isLoading = false, this.users = const [], this.error});

  AdminState copyWith({bool? isLoading, List<dynamic>? users, String? error}) {
    return AdminState(
      isLoading: isLoading ?? this.isLoading,
      users: users ?? this.users,
      error: error,
    );
  }
}

class AdminController extends StateNotifier<AdminState> {
  final AdminRepository _repository;

  AdminController(this._repository) : super(AdminState());

  Future<void> searchUsers(String query) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final users = await _repository.searchUsers(query);
      state = state.copyWith(isLoading: false, users: users);
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  Future<void> resetDevice(int userId, Function onSuccess) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      await _repository.resetDevice(userId);
      state = state.copyWith(isLoading: false);
      onSuccess();
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }
}

final adminControllerProvider = StateNotifierProvider<AdminController, AdminState>((ref) {
  final repo = ref.watch(adminRepositoryProvider);
  return AdminController(repo);
});
