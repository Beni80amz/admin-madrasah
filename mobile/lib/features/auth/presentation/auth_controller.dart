import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../data/auth_repository.dart';

// State for Auth
class AuthState {
  final bool isAuthenticated;
  final bool isLoading;
  final String? error;
  final Map<String, dynamic>? user;

  AuthState({
    required this.isAuthenticated,
    this.isLoading = false,
    this.error,
    this.user,
  });

  factory AuthState.initial() {
    return AuthState(isAuthenticated: false);
  }
  
  AuthState copyWith({
    bool? isAuthenticated,
    bool? isLoading,
    String? error,
    Map<String, dynamic>? user,
  }) {
    return AuthState(
      isAuthenticated: isAuthenticated ?? this.isAuthenticated,
      isLoading: isLoading ?? this.isLoading,
      error: error,
      user: user ?? this.user,
    );
  }
}

class AuthController extends StateNotifier<AuthState> {
  final AuthRepository _repository;

  AuthController(this._repository) : super(AuthState.initial()) {
    checkLoginStatus();
  }

  void checkLoginStatus() {
    final token = _repository.getToken();
    final user = _repository.getUserData();
    if (token != null) {
      state = state.copyWith(isAuthenticated: true, user: user);
    }
  }

  Future<void> login(String email, String password) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final data = await _repository.login(email, password);
      // Data contains {token, user, user_type, profile}
      // Parse meaningful user object
      final userData = {
        'user': data['user'],
        'user_type': data['user_type'],
        'profile': data['profile']
      };
      
      state = state.copyWith(
        isAuthenticated: true,
        isLoading: false,
        user: userData,
      );
    } catch (e) {
      state = state.copyWith(
        isLoading: false,
        error: e.toString().replaceAll('Exception: ', ''),
      );
    }
  }

  Future<void> logout() async {
    state = state.copyWith(isLoading: true);
    await _repository.logout();
    state = AuthState.initial();
  }
}

final authControllerProvider = StateNotifierProvider<AuthController, AuthState>((ref) {
  final repo = ref.watch(authRepositoryProvider);
  return AuthController(repo);
});
