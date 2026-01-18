import 'dart:typed_data';

import 'package:intl/intl.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:printing/printing.dart';
import '../../../../core/constants/api_constants.dart';

class HistoryPdfService {
  Future<void> generateAndPrintPdf({
    required List<dynamic> historyData,
    required DateTime month,
    Map<String, dynamic>? schoolProfile,
    Map<String, dynamic>? userProfile,
  }) async {
    final pdf = pw.Document();
    
    // Sort data by date
    final sortedData = List.from(historyData)
      ..sort((a, b) => a['date'].compareTo(b['date']));

    final monthStr = DateFormat('MMMM yyyy', 'id_ID').format(month);
    
    // Construct Login URL
    final baseUrl = ApiConstants.baseUrl;
    // Ensure no trailing slash
    final cleanBaseUrl = baseUrl.endsWith('/') ? baseUrl.substring(0, baseUrl.length - 1) : baseUrl;
    
    final loginUrl = '$cleanBaseUrl/admin/login';

    pdf.addPage(
      pw.MultiPage(
        pageFormat: PdfPageFormat.a4,
        margin: const pw.EdgeInsets.all(32),
        build: (context) {
          return [
            if (schoolProfile != null) _buildKop(schoolProfile),
            pw.SizedBox(height: 20),
            _buildTitle(monthStr, userProfile),
            pw.SizedBox(height: 24),
            _buildTable(sortedData),
            pw.SizedBox(height: 32),
            _buildFooter(schoolProfile, loginUrl),
          ];
        },
      ),
    );

    await Printing.layoutPdf(
      onLayout: (format) async => pdf.save(),
      name: 'Laporan Absensi $monthStr',
    );
  }

  pw.Widget _buildKop(Map<String, dynamic> profile) {
    return pw.Column(
      children: [
        pw.Text(
          'LAPORAN ABSENSI',
          style: pw.TextStyle(fontWeight: pw.FontWeight.bold, fontSize: 14),
        ),
        pw.SizedBox(height: 4),
        pw.Text(
          (profile['nama_madrasah'] ?? 'MADRASAH').toString().toUpperCase(),
          style: pw.TextStyle(fontWeight: pw.FontWeight.bold, fontSize: 16),
        ),
        pw.SizedBox(height: 4),
        pw.Text(
          '${profile['alamat'] ?? ''}',
          style: const pw.TextStyle(fontSize: 10),
          textAlign: pw.TextAlign.center,
        ),
        pw.Text(
          'Telp: ${profile['no_hp'] ?? '-'} | Email: ${profile['email'] ?? '-'}',
          style: const pw.TextStyle(fontSize: 10),
          textAlign: pw.TextAlign.center,
        ),
        pw.SizedBox(height: 8),
        pw.Divider(thickness: 2),
      ],
    );
  }

  pw.Widget _buildTitle(String monthStr, Map<String, dynamic>? user) {
    return pw.Row(
      mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
      children: [
        pw.Text('Periode: $monthStr', style: const pw.TextStyle(fontSize: 11)),
        if (user != null)
          pw.Text(
            'Nama: ${(user['nama_lengkap'] ?? user['name'] ?? '-').toString().toUpperCase()}',
             style: pw.TextStyle(fontWeight: pw.FontWeight.bold, fontSize: 11),
          ),
      ],
    );
  }

  pw.Widget _buildFooter(Map<String, dynamic>? profile, String verificationUrl) {
    final now = DateTime.now();
    final dateStr = DateFormat('d MMMM yyyy', 'id_ID').format(now);
    
    return pw.Row(
      mainAxisAlignment: pw.MainAxisAlignment.end,
      children: [
        pw.Column(
          crossAxisAlignment: pw.CrossAxisAlignment.center,
          children: [
            pw.Text('Depok, $dateStr', style: const pw.TextStyle(fontSize: 11)),
            pw.Text('Mengetahui,', style: const pw.TextStyle(fontSize: 11)),
            pw.Text('Kepala Madrasah', style: const pw.TextStyle(fontSize: 11)),
            pw.SizedBox(height: 8),
            pw.Container(
              width: 80,
              height: 80,
              child: pw.BarcodeWidget(
                data: verificationUrl,
                barcode: pw.Barcode.qrCode(),
                drawText: false,
              ),
            ),
            pw.SizedBox(height: 4),
            pw.Text(
              profile?['nama_kepala_madrasah'] ?? '.........................',
              style: pw.TextStyle(fontWeight: pw.FontWeight.bold, fontSize: 11),
            ),
            pw.Text(
              'NIP. ${profile?['nip_kepala_madrasah'] ?? '-'}',
              style: const pw.TextStyle(fontSize: 10),
            ),
          ],
        ),
      ],
    );
  }

  pw.Widget _buildTable(List<dynamic> data) {
    return pw.TableHelper.fromTextArray(
      headers: ['No', 'Tanggal', 'Hari', 'Masuk', 'Pulang', 'Status', 'Telat'],
      columnWidths: {
        0: const pw.FixedColumnWidth(30), // No
        1: const pw.FlexColumnWidth(3),   // Tanggal
        2: const pw.FlexColumnWidth(2),   // Hari
        3: const pw.FlexColumnWidth(2),   // Masuk
        4: const pw.FlexColumnWidth(2),   // Pulang
        5: const pw.FlexColumnWidth(2),   // Status
        6: const pw.FlexColumnWidth(1.5), // Telat
      },
      data: List<List<dynamic>>.generate(data.length, (index) {
        final item = data[index];
        final date = DateTime.parse(item['date']);
        final dateStr = DateFormat('d MMMM yyyy', 'id_ID').format(date);
        final dayStr = DateFormat('EEEE', 'id_ID').format(date);
        final status = (item['status'] ?? '-').toString();
        // Capitalize status properly (e.g. "hadir" -> "Hadir")
        final statusFormatted = status.isNotEmpty 
            ? '${status[0].toUpperCase()}${status.substring(1)}' 
            : '-';

        final timeIn = _formatTime(item['time_in']);
        final timeOut = _formatTime(item['time_out']);
        
        // Handle late minutes
        String lateStr = '0m';
        if (item['keterlambatan'] != null) {
          final lateVal = item['keterlambatan'];
          if (lateVal is int && lateVal > 0) {
            lateStr = '${lateVal}m';
          } else if (lateVal is String && lateVal != '0' && lateVal != '') {
             lateStr = '${lateVal}m';
          }
        }

        return [
          '${index + 1}',
          dateStr,
          dayStr,
          timeIn,
          timeOut,
          statusFormatted,
          lateStr,
        ];
      }),
      headerStyle: pw.TextStyle(fontWeight: pw.FontWeight.bold, fontSize: 10),
      cellStyle: const pw.TextStyle(fontSize: 10),
      headerDecoration: pw.BoxDecoration(
        border: pw.Border.all(width: 1),
        color: PdfColors.grey200,
      ),
      rowDecoration: const pw.BoxDecoration(
        border: pw.Border(
          bottom: pw.BorderSide(width: 0.5, color: PdfColors.grey400),
          left: pw.BorderSide(width: 0.5, color: PdfColors.grey400),
          right: pw.BorderSide(width: 0.5, color: PdfColors.grey400),
        ),
      ),
      cellAlignment: pw.Alignment.center,
      cellAlignments: {
        0: pw.Alignment.center,
        1: pw.Alignment.centerLeft,
        2: pw.Alignment.centerLeft,
      },
    );
  }

  String _formatTime(dynamic timeString) {
    if (timeString == null) return '-';
    final str = timeString.toString();
    if (str.isEmpty) return '-';
    
    // Attempt parse if it looks like full date
    if (str.contains('T') || str.contains('-')) {
       try {
         return DateFormat('HH:mm:ss').format(DateTime.parse(str));
       } catch (e) {
         return str; 
       }
    }
    
    return str;
  }
}
