import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../auth/presentation/auth_controller.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../admin/presentation/screens/user_management_screen.dart';

class ProfileScreen extends ConsumerWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final user = ref.watch(authControllerProvider).user;
    final userData = user?['user'];
    final profile = user?['profile'];
    
    return Scaffold(
      appBar: AppBar(
        title: Text('Profil Saya', style: GoogleFonts.lexend()),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            Center(
              child: CircleAvatar(
                radius: 50,
                backgroundColor: AppTheme.primaryColor.withOpacity(0.1),
                backgroundImage: profile?['photo'] != null ? NetworkImage(profile['photo']) : null,
                child: profile?['photo'] == null 
                    ? const Icon(Icons.person, size: 50, color: AppTheme.primaryColor) 
                    : null,
              ),
            ),
            const SizedBox(height: 16),
            Text(
              userData?['name'] ?? 'User',
              style: GoogleFonts.lexend(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            Text(
              userData?['email'] ?? '-',
              style: GoogleFonts.lexend(color: Colors.grey),
            ),
            const SizedBox(height: 32),
            
            _buildInfoTile(Icons.badge, 'NIP / NIS', profile != null ? (profile['nip'] ?? profile['nis_lokal'] ?? '-') : '-'),
            _buildInfoTile(Icons.phone, 'No. Telepon', profile?['no_hp'] ?? '-'),
            _buildInfoTile(Icons.location_on, 'Alamat', profile?['alamat'] ?? '-'),
            
            _buildInfoTile(Icons.location_on, 'Alamat', profile?['alamat'] ?? '-'),
            
            // Admin Menu
            if (userData?['email'] == 'admin@admin.com' || userData?['name'] == 'Administrator') ...[
              const SizedBox(height: 32),
              SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton.icon(
                  onPressed: () {
                    Navigator.push(context, MaterialPageRoute(builder: (_) => const UserManagementScreen()));
                  },
                  icon: const Icon(Icons.admin_panel_settings),
                  label: const Text('Admin: Reset Perangkat User'),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blueGrey,
                  ),
                ),
              ),
            ],

            const SizedBox(height: 32),
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton.icon(
                onPressed: () {
                   showDialog(
                     context: context,
                     builder: (context) => AlertDialog(
                       title: Text('Logout'),
                       content: Text('Yakin ingin keluar aplikasi?'),
                       actions: [
                         TextButton(onPressed: ()=>Navigator.pop(context), child: Text('Batal')),
                           TextButton(
                           onPressed: () {
                             Navigator.pop(context); // Close dialog
                             // Navigator.pop(context); // Removed: ProfileScreen is not a pushed route, it's part of BottomNav. 
                             // AuthWrapper will handle switching to LoginScreen when state changes.
                             ref.read(authControllerProvider.notifier).logout();
                           }, 
                           child: Text('Ya, Keluar', style: TextStyle(color: Colors.red))
                         ),
                       ],
                     )
                   );
                },
                icon: const Icon(Icons.logout),
                label: const Text('Keluar Aplikasi'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.errorColor,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoTile(IconData icon, String label, String value) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 4, offset: Offset(0, 2))],
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: Colors.grey[100],
              borderRadius: BorderRadius.circular(8),
            ),
            child: Icon(icon, color: Colors.grey[600]),
          ),
          const SizedBox(width: 16),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(label, style: GoogleFonts.lexend(fontSize: 12, color: Colors.grey)),
              Text(value, style: GoogleFonts.lexend(fontSize: 16, fontWeight: FontWeight.w500)),
            ],
          ),
        ],
      ),
    );
  }
}
