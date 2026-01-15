import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'admin_controller.dart';
import '../../../../core/theme/app_theme.dart';

class UserManagementScreen extends ConsumerStatefulWidget {
  const UserManagementScreen({super.key});

  @override
  ConsumerState<UserManagementScreen> createState() => _UserManagementScreenState();
}

class _UserManagementScreenState extends ConsumerState<UserManagementScreen> {
  final TextEditingController _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    // Load initial empty list or all users
    Future.microtask(() => ref.read(adminControllerProvider.notifier).searchUsers(''));
  }

  void _onResetDevice(int userId, String userName) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Reset Perangkat?'),
        content: Text('Anda yakin ingin mereset perangkat milik "$userName"?\n\nUser akan bisa login kembali di HP baru.'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Batal')),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red, foregroundColor: Colors.white),
            onPressed: () {
              Navigator.pop(ctx);
              ref.read(adminControllerProvider.notifier).resetDevice(userId, () {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Perangkat berhasil direset!'), backgroundColor: Colors.green),
                );
              });
            }, 
            child: const Text('Reset Sekarang'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(adminControllerProvider);

    return Scaffold(
      appBar: AppBar(
        title: Text('Manajemen Perangkat', style: GoogleFonts.lexend(fontWeight: FontWeight.bold)),
        elevation: 0,
        backgroundColor: Colors.white,
        foregroundColor: AppTheme.textMainColor,
      ),
      body: Column(
        children: [
          // Search Bar
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: 'Cari Nama / NIP...',
                prefixIcon: const Icon(Icons.search),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                filled: true,
                fillColor: Colors.grey.shade50,
              ),
              onSubmitted: (value) {
                ref.read(adminControllerProvider.notifier).searchUsers(value);
              },
            ),
          ),
          
          Expanded(
            child: state.isLoading
                ? const Center(child: CircularProgressIndicator())
                : state.users.isEmpty
                    ? Center(child: Text('User tidak ditemukan', style: GoogleFonts.lexend()))
                    : ListView.separated(
                        itemCount: state.users.length,
                        separatorBuilder: (_, __) => const Divider(),
                        itemBuilder: (context, index) {
                          final user = state.users[index];
                          final teacher = user['teacher'];
                          final String displayName = teacher != null ? teacher['nama_lengkap'] : user['name'];
                          final String displaySub = teacher != null ? 'NIP: ${teacher['nip'] ?? '-'}' : user['email'];
                          final bool hasDevice = user['device_id'] != null;

                          return ListTile(
                            leading: CircleAvatar(
                              backgroundColor: AppTheme.primaryColor.withOpacity(0.1),
                              child: Text(displayName[0], style: TextStyle(color: AppTheme.primaryColor)),
                            ),
                            title: Text(displayName, style: GoogleFonts.lexend(fontWeight: FontWeight.bold)),
                            subtitle: Text(displaySub, style: GoogleFonts.lexend(fontSize: 12, color: Colors.grey)),
                            trailing: hasDevice 
                                ? ElevatedButton.icon(
                                    onPressed: () => _onResetDevice(user['id'], displayName),
                                    icon: const Icon(Icons.phonelink_erase, size: 16),
                                    label: const Text('Reset'),
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: Colors.red.withOpacity(0.1),
                                      foregroundColor: Colors.red,
                                      elevation: 0,
                                    ),
                                  )
                                : const Chip(label: Text('No Device'), backgroundColor: Colors.transparent),
                          );
                        },
                      ),
          ),
        ],
      ),
    );
  }
}
