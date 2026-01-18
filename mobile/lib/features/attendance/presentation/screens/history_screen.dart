import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../attendance_controller.dart';
import '../../../../features/auth/data/auth_repository.dart';
import '../../../../core/theme/app_theme.dart';
import '../services/history_pdf_service.dart';

class HistoryScreen extends ConsumerStatefulWidget {
  const HistoryScreen({super.key});

  @override
  ConsumerState<HistoryScreen> createState() => _HistoryScreenState();
}

class _HistoryScreenState extends ConsumerState<HistoryScreen> {
  DateTime _selectedDate = DateTime.now();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _loadData();
    });
  }

  void _loadData() {
    ref.read(attendanceControllerProvider.notifier).loadHistory(
      month: _selectedDate.month,
      year: _selectedDate.year,
    );
  }

  Future<void> _pickMonth() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: _selectedDate,
      firstDate: DateTime(2020),
      lastDate: DateTime(2030),
      initialDatePickerMode: DatePickerMode.year,
    ); 
    // Note: Standard DatePicker doesn't support month-only easily without plugins.
    // For MVP, we use DatePicker and just take month/year.
    
    if (picked != null) {
      setState(() {
        _selectedDate = picked;
      });
      _loadData();
    }
  }

  Future<void> _exportPdf() async {
    final attendanceState = ref.read(attendanceControllerProvider);
    if (attendanceState.history.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Tidak ada data untuk diexport')),
      );
      return;
    }

    try {
      final authRepo = ref.read(authRepositoryProvider);
      final userData = authRepo.getUserData();

      await HistoryPdfService().generateAndPrintPdf(
        historyData: attendanceState.history, 
        month: _selectedDate,
        schoolProfile: userData?['school_profile'] != null 
            ? Map<String, dynamic>.from(userData!['school_profile']) 
            : null,
        userProfile: (userData?['profile'] ?? userData?['user']) != null
            ? Map<String, dynamic>.from(userData?['profile'] ?? userData?['user'])
            : null,
      );
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal export PDF: $e')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final attendanceState = ref.watch(attendanceControllerProvider);
    
    return Scaffold(
      appBar: AppBar(
        title: Text('Riwayat Absensi', style: GoogleFonts.lexend()),
        actions: [
          IconButton(
            icon: const Icon(Icons.calendar_month),
            onPressed: _pickMonth,
          ),
          IconButton(
            icon: const Icon(Icons.print),
            tooltip: 'Export PDF',
            onPressed: _exportPdf,
          ),
        ],
      ),
      body: Column(
        children: [
          // Month Selector Display
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.white,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  DateFormat('MMMM yyyy', 'id_ID').format(_selectedDate),
                  style: GoogleFonts.lexend(fontSize: 18, fontWeight: FontWeight.bold),
                ),
                TextButton(
                  onPressed: _pickMonth,
                  child: const Text('Ganti Bulan'),
                ),
              ],
            ),
          ),
          
          Expanded(
            child: attendanceState.isLoading
                ? const Center(child: CircularProgressIndicator())
                : attendanceState.history.isEmpty
                    ? Center(child: Text('Tidak ada data absensi', style: GoogleFonts.lexend()))
                    : ListView.builder(
                        padding: const EdgeInsets.all(16),
                        itemCount: attendanceState.history.length,
                        itemBuilder: (context, index) {
                          final item = attendanceState.history[index];
                          return _buildHistoryItem(item);
                        },
                      ),
          ),
        ],
      ),
    );
  }

  Widget _buildHistoryItem(Map<String, dynamic> item) {
    final date = DateTime.parse(item['date']);
    final status = item['status'] ?? 'unknown';
    
    Color statusColor = Colors.grey;
    if (status == 'hadir') statusColor = AppTheme.successColor;
    if (status == 'telat') statusColor = Colors.orange;
    if (status == 'izin' || status == 'sakit') statusColor = Colors.blue;
    if (status == 'alpha') statusColor = AppTheme.errorColor;

    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      DateFormat('EEEE, d MMMM', 'id_ID').format(date),
                      style: GoogleFonts.lexend(fontWeight: FontWeight.bold),
                    ),
                    Text(
                      status.toUpperCase(),
                      style: GoogleFonts.lexend(
                        color: statusColor,
                        fontWeight: FontWeight.bold,
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
                if (status == 'hadir' || status == 'telat')
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      _buildTimeRow('Masuk', item['time_in']),
                      const SizedBox(height: 4),
                      _buildTimeRow('Pulang', item['time_out']),
                    ],
                  ),
              ],
            ),
            if (item['keterlambatan'] != null && (item['keterlambatan'] as int) > 0)
              Padding(
                padding: const EdgeInsets.only(top: 8),
                child: Row(
                  children: [
                    const Icon(Icons.warning, size: 14, color: Colors.orange),
                    const SizedBox(width: 4),
                    Text(
                      'Telat ${item['keterlambatan']} menit',
                      style: GoogleFonts.lexend(fontSize: 12, color: Colors.orange),
                    ),
                  ],
                ),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildTimeRow(String label, String? timeString) {
    String time = '--:--';
    if (timeString != null) {
      time = _formatTime(timeString);
    }
    return Row(
      children: [
        Text('$label: ', style: GoogleFonts.lexend(fontSize: 12, color: Colors.grey)),
        Text(time, style: GoogleFonts.lexend(fontSize: 12, fontWeight: FontWeight.bold)),
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
