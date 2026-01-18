import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart'; 
import '../../../auth/presentation/auth_controller.dart';
import '../../../attendance/presentation/attendance_controller.dart';
import '../../../attendance/presentation/screens/scan_screen.dart';
import '../../../attendance/presentation/screens/history_screen.dart';
import '../../../leave/presentation/screens/leave_request_screen.dart';
import '../../../profile/presentation/screens/profile_screen.dart';
import '../../../../core/theme/app_theme.dart';

class HomeScreen extends ConsumerStatefulWidget {
  const HomeScreen({super.key});

  @override
  ConsumerState<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends ConsumerState<HomeScreen> {
  int _currentIndex = 0;
  
  final List<Widget> _screens = [
    const _DashboardContent(), // 0
    const HistoryScreen(),     // 1
    const SizedBox(),          // 2 (Spacer for FAB)
    const Center(child: Text('Inbox (Coming Soon)')), // 3
    const ProfileScreen(),     // 4
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.scaffoldBackgroundColor,
      // Ensure we don't go out of bounds if something is wrong, though with 5 items it should be fine.
      body: _screens.length > _currentIndex ? _screens[_currentIndex] : _screens[0],
      floatingActionButton: Container(
        height: 64,
        width: 64,
        margin: const EdgeInsets.only(top: 30),
        child: FloatingActionButton(
          onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ScanScreen())),
          backgroundColor: AppTheme.primaryColor,
          elevation: 4,
          shape: const CircleBorder(),
          child: const Icon(Icons.qr_code_scanner, size: 32, color: Colors.white),
        ),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          boxShadow: [
             BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, -5)),
          ],
        ),
        child: BottomNavigationBar(
          currentIndex: _currentIndex,
          onTap: (index) {
             // Prevent tapping the middle spacer (index 2)
             if (index == 2) return;
             setState(() => _currentIndex = index);
          },
          backgroundColor: Colors.white,
          selectedItemColor: AppTheme.primaryColor,
          unselectedItemColor: Colors.grey,
          type: BottomNavigationBarType.fixed,
          showUnselectedLabels: true,
          selectedLabelStyle: GoogleFonts.lexend(fontSize: 12, fontWeight: FontWeight.w600),
          unselectedLabelStyle: GoogleFonts.lexend(fontSize: 12),
          items: const [
            BottomNavigationBarItem(icon: Icon(Icons.home_outlined), activeIcon: Icon(Icons.home), label: 'Home'),
            BottomNavigationBarItem(icon: Icon(Icons.history_outlined), activeIcon: Icon(Icons.history), label: 'History'),
             // Placeholder for spacing around FAB
            BottomNavigationBarItem(icon: SizedBox.shrink(), label: ''), 
            BottomNavigationBarItem(icon: Icon(Icons.mail_outline), activeIcon: Icon(Icons.mail), label: 'Inbox'),
            BottomNavigationBarItem(icon: Icon(Icons.person_outline), activeIcon: Icon(Icons.person), label: 'Profile'),
          ],
        ),
      ),
    );
  }
}

class _DashboardContent extends ConsumerStatefulWidget {
  const _DashboardContent();

  @override
  ConsumerState<_DashboardContent> createState() => _DashboardContentState();
}

class _DashboardContentState extends ConsumerState<_DashboardContent> {
  late Timer _timer;
  DateTime _now = DateTime.now();

  @override
  void initState() {
    super.initState();
    _startTimer();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      ref.read(attendanceControllerProvider.notifier).loadTodayAttendance();
      ref.read(attendanceControllerProvider.notifier).loadWeeklyTimeline();
    });
  }

  void _startTimer() {
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (mounted) {
        setState(() => _now = DateTime.now());
      }
    });
  }

  @override
  void dispose() {
    _timer.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(authControllerProvider).user;
    final userName = user?['user']?['name'] ?? 'User';
    final userNip = user?['profile']?['nip'] ?? user?['profile']?['nis_lokal'] ?? '-';
    // final userClass = user?['profile']?['kelas'] ?? 'Staff';
    final profilePhoto = user?['profile']?['photo'];

    final attendanceState = ref.watch(attendanceControllerProvider);
    final today = attendanceState.todayAttendance;
    final weekly = attendanceState.weeklyTimeline;

    return SingleChildScrollView(
      padding: const EdgeInsets.only(bottom: 100), // Space for FAB
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Top Bar
          Container(
            padding: const EdgeInsets.fromLTRB(20, 50, 20, 20),
            color: AppTheme.surfaceLightColor,
            child: Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Selamat Pagi,', style: GoogleFonts.lexend(color: AppTheme.textSubColor, fontSize: 14)),
                      const SizedBox(height: 4),
                      Text(userName, style: GoogleFonts.lexend(color: AppTheme.textMainColor, fontSize: 20, fontWeight: FontWeight.bold), maxLines: 1, overflow: TextOverflow.ellipsis),
                      const SizedBox(height: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                          color: AppTheme.primaryColor.withOpacity(0.1),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          'ID: $userNip',
                          style: GoogleFonts.lexend(color: AppTheme.primaryDarkColor, fontSize: 12, fontWeight: FontWeight.bold),
                        ),
                      ),
                    ],
                  ),
                ),
                CircleAvatar(
                  radius: 25,
                  backgroundColor: Colors.grey[200],
                  backgroundImage: profilePhoto != null ? NetworkImage(profilePhoto) : null,
                  child: profilePhoto == null ? const Icon(Icons.person, color: Colors.grey) : null,
                ),
              ],
            ),
          ),

          Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              children: [
                // Hero Card
                Container(
                  width: double.infinity,
                  height: 180, // Fixed height for aesthetic
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(24),
                    color: AppTheme.darkBackgroundColor,
                    image: const DecorationImage(
                      image: NetworkImage("https://lh3.googleusercontent.com/aida-public/AB6AXuAJsuNZUKMNtYZFbro1uvm_Tf2VqZ53V7WX3oEqv6owZMbTEHH15wJWF3z3mBtMlUQXIqoQq5GDDHH2B2CyfUpnpT-tNAqzlsOJDC7y4pRHiWF-sDAQoSBHws2KPmKilI2g3xqLUFRPgJgE0JVKmUp-QBMUGSUFDEiKCs1n-Nqm7_mOpAoKqPb_Zk25E2xtDyg0GfxkUYBkSpvdsG7wdJQsLnD2I5SohsyKIaG2s8VB4XsBFlUA_gsxhtSmyfPys604XYVX1FKykEg"),
                      fit: BoxFit.cover,
                      opacity: 0.4, // Dim the image
                    ),
                    gradient: LinearGradient(
                      begin: Alignment.bottomCenter,
                      end: Alignment.topCenter,
                      colors: [
                        Colors.black.withOpacity(0.8),
                        Colors.transparent,
                      ],
                    ),
                  ),
                  child: Stack(
                    children: [
                       Padding(
                         padding: const EdgeInsets.all(24),
                         child: Column(
                           crossAxisAlignment: CrossAxisAlignment.start,
                           mainAxisAlignment: MainAxisAlignment.center,
                           children: [
                             Row(children: [
                               const Icon(Icons.access_time_filled, color: AppTheme.primaryColor, size: 20),
                               const SizedBox(width: 8),
                               Text("Waktu Sekarang", style: GoogleFonts.lexend(color: Colors.white70, fontSize: 14)),
                             ]),
                             const SizedBox(height: 8),
                             Text(
                               DateFormat('HH:mm').format(_now),
                               style: GoogleFonts.lexend(color: Colors.white, fontSize: 48, fontWeight: FontWeight.bold, height: 1),
                             ),
                             Text(
                               DateFormat('EEEE, d MMMM yyyy', 'id_ID').format(_now),
                               style: GoogleFonts.lexend(color: Colors.white70, fontSize: 14),
                             ),
                           ],
                         ),
                       ),
                       Positioned(
                         bottom: 24,
                         right: 24,
                         child: ElevatedButton.icon(
                           onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ScanScreen())), 
                           icon: const Icon(Icons.fingerprint), 
                           label: const Text("Absen"),
                           style: ElevatedButton.styleFrom(
                             backgroundColor: AppTheme.primaryColor,
                             foregroundColor: Colors.white,
                             shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                             padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                           ),
                         ),
                       )
                    ],
                  ),
                ),

                const SizedBox(height: 20),

                // Stats Row
                Row(
                  children: [
                      Expanded(
                        child: _buildStatCard(
                          'Masuk',
                          today != null && today['time_in'] != null
                              ? _formatTime(today['time_in'])
                              : '--:--',
                          Icons.login_rounded,
                          AppTheme.primaryDarkColor,
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: _buildStatCard(
                          'Pulang',
                          today != null && today['time_out'] != null
                              ? _formatTime(today['time_out'])
                              : '--:--',
                          Icons.logout_rounded,
                          Colors.orange,
                        ),
                      ),
                     const SizedBox(width: 12),
                    Expanded(child: _buildStatCard("Status", 
                      today != null ? "Hadir" : "-", // Simplification
                      Icons.verified_user_rounded, 
                      Colors.blue
                    )),
                  ],
                ),

                const SizedBox(height: 24),

                // Menu Grid (Menu Cepat)
                 Align(alignment: Alignment.centerLeft, child: Text("Menu Cepat", style: GoogleFonts.lexend(fontSize: 16, fontWeight: FontWeight.bold))),
                 const SizedBox(height: 12),
                 Row(
                   mainAxisAlignment: MainAxisAlignment.spaceBetween,
                   children: [
                     _buildQuickMenuItem(context, "Izin/Sakit", Icons.description_outlined, Colors.purple, () => Navigator.push(context, MaterialPageRoute(builder: (_) => const LeaveRequestScreen()))),
                     _buildQuickMenuItem(context, "Riwayat", Icons.history, Colors.blue, () => Navigator.push(context, MaterialPageRoute(builder: (_) => const HistoryScreen()))),
                     _buildQuickMenuItem(context, "Jadwal", Icons.calendar_today_outlined, Colors.orange, (){
                       ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Fitur Jadwal akan segera tersedia')));
                     }),
                     _buildQuickMenuItem(context, "Tugas", Icons.assignment_outlined, Colors.teal, (){
                       ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Fitur Tugas akan segera tersedia')));
                     }),
                   ],
                 ),

                 const SizedBox(height: 24),

                 // Weekly Recap
                 Row(
                   mainAxisAlignment: MainAxisAlignment.spaceBetween,
                   children: [
                     Text("Rekap Minggu Ini", style: GoogleFonts.lexend(fontSize: 16, fontWeight: FontWeight.bold)),
                     InkWell(
                        onTap: () {
                           Navigator.push(context, MaterialPageRoute(builder: (_) => const HistoryScreen()));
                        },
                        child: Padding(
                          padding: const EdgeInsets.all(4.0),
                          child: Text("Lihat Semua", style: GoogleFonts.lexend(fontSize: 12, color: AppTheme.primaryColor, fontWeight: FontWeight.bold)),
                        ),
                     ),
                   ],
                 ),
                 const SizedBox(height: 12),
                 Container(
                   padding: const EdgeInsets.all(16),
                   decoration: BoxDecoration(
                     color: Colors.white,
                     borderRadius: BorderRadius.circular(16),
                     boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10)]
                   ),
                   child: Row(
                     mainAxisAlignment: MainAxisAlignment.spaceBetween,
                     children: _buildWeeklyRecapChildren(weekly),
                   ),
                 )
              ],
            ),
          ),
        ],
      ),
    );
  }

  // Helper to build children dynamically
  List<Widget> _buildWeeklyRecapChildren(List<dynamic>? weeklyData) {
      if (weeklyData == null || weeklyData.isEmpty) {
          // Fallback static or empty
          return [const Text("Memuat data...")];
      }
      
      List<Widget> children = [];
      // Backend returns a list (0=Sen, 1=Sel, etc.)
      int index = 0;
      for (var dayData in weeklyData) {
          index++;
          // Logic: 
          // If day is Saturday (index 6, assuming 1-based logic in loop count or check date)
          // Actually, backend returns items. We check 'label'.
          // If label is 'Libur' and day is Saturday, maybe hide?
          // Let's check the date to be sure of the day.
          
          bool shouldShow = true;
          if (dayData['date'] != null) {
              final date = DateTime.parse(dayData['date']);
              // If Saturday (6) and label is Libur, hide it per requirements
              // "Jika ... sampai Jumat (Sabtu Minggu Libur), maka hari yang ditampilkan Senin-Jumat"
              if (date.weekday == 6) { // Saturday
                  if (dayData['label'] == 'Libur') {
                      shouldShow = false;
                  }
              }
              // If Sunday (7), we usually don't get it from backend loop 0-5. 
          }
          
          if (shouldShow) {
              children.add(_buildDayRecapItem(dayData));
          }
      }
      
      // If we have fewer than 5 items displayed, maybe alignment looks weird?
      // Row is SpaceBetween. 
      return children;
  }

  Widget _buildStatCard(String label, String value, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.shade100),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 8)],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(label, style: GoogleFonts.lexend(fontSize: 12, color: Colors.grey)),
              Icon(icon, size: 16, color: color),
            ],
          ),
          const SizedBox(height: 8),
          Text(value, style: GoogleFonts.lexend(fontSize: 16, fontWeight: FontWeight.bold, color: AppTheme.textMainColor)),
        ],
      ),
    );
  }

  Widget _buildQuickMenuItem(BuildContext context, String label, IconData icon, Color color, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: color.withOpacity(0.1),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Icon(icon, color: color, size: 24),
          ),
          const SizedBox(height: 8),
          Text(label, style: GoogleFonts.lexend(fontSize: 12, fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }
  
  Widget _buildDayRecapItem(dynamic dayData) {
     // Weekday determination 
     String dayLabel = '?';
     if (dayData['date'] != null) {
         final date = DateTime.parse(dayData['date']);
         final days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
         dayLabel = days[date.weekday - 1];
     }

     String status = dayData['label'] ?? '--';
     String colorStr = dayData['color'] ?? 'gray';
     Color color = Colors.grey;
     IconData icon = Icons.remove;

     // Map Backend Colors to Flutter Colors
     switch (colorStr) {
         case 'green':
            color = Colors.green;
            icon = Icons.check;
            break;
         case 'yellow':
            color = Colors.orange; // Yellow is hard to read
            icon = Icons.access_time; // Late/Sick
            if (status.toLowerCase().contains('sakit')) icon = Icons.sick;
            break;
         case 'blue':
            color = Colors.blue;
            icon = Icons.assignment_ind; // Permit
            break;
         case 'red':
            color = Colors.red;
            icon = Icons.close; // Alpha
            break;
         default:
            color = Colors.grey;
            icon = Icons.remove;
     }
     
     // Override icon for 'Hadir' (time label)
     if (color == Colors.green) {
         icon = Icons.check; 
     }

     return Column(
       children: [
         Text(dayLabel, style: GoogleFonts.lexend(fontSize: 12, color: Colors.grey)),
         const SizedBox(height: 8),
         Container(
           height: 40, width: 40,
           decoration: BoxDecoration(
             color: color.withOpacity(0.1),
             borderRadius: BorderRadius.circular(8),
             border: Border.all(color: color.withOpacity(0.2))
           ),
           child: Icon(
             icon,
             color: color,
             size: 20,
           ),
         ),
         const SizedBox(height: 4),
         Text(status, style: GoogleFonts.lexend(fontSize: 10, color: color, fontWeight: FontWeight.bold)),
       ],
     );
  }


  String _formatTime(String timeString) {
    try {
      if (timeString.isEmpty) return '--:--';
      
      // If it's already "HH:mm:ss" or similar (no 'T' or date part)
      if (!timeString.contains('T') && !timeString.contains('-')) {
        final parts = timeString.split(':');
        if (parts.length >= 2) {
          return '${parts[0]}:${parts[1]}';
        }
        return timeString;
      }
      
      // Try full parsing
      return DateFormat('HH:mm').format(DateTime.parse(timeString));
    } catch (e) {
      return timeString; // Fallback to original string if all else fails
    }
  }
}
