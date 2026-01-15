import 'dart:async';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import '../attendance_controller.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../features/settings/presentation/server_settings_screen.dart';

class ScanScreen extends ConsumerStatefulWidget {
  const ScanScreen({super.key});

  @override
  ConsumerState<ScanScreen> createState() => _ScanScreenState();
}

class _ScanScreenState extends ConsumerState<ScanScreen> with SingleTickerProviderStateMixin, WidgetsBindingObserver {
  final MobileScannerController _scannerController = MobileScannerController(
    detectionSpeed: DetectionSpeed.noDuplicates,
    returnImage: false,
  );
  
  // Animation for scanning line
  late AnimationController _animationController;
  late Animation<double> _animation;

  // State
  bool _isQrMode = true;
  File? _selfieImage;
  String? _qrCode;
  bool _isLocationLoading = false;
  Position? _currentPosition;
  bool _isInsideArea = false; // Mock status
  
  // Clock Stream
  late Stream<DateTime> _clockStream;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    
    // Setup Animation
    _animationController = AnimationController(vsync: this, duration: const Duration(seconds: 3));
    _animation = Tween<double>(begin: 0, end: 1).animate(_animationController)
      ..addListener(() => setState(() {}))
      ..addStatusListener((status) {
        if (status == AnimationStatus.completed) {
          _animationController.reverse();
        } else if (status == AnimationStatus.dismissed) {
          _animationController.forward();
        }
      });
    _animationController.forward();
    
    // Setup Clock
    _clockStream = Stream.periodic(const Duration(seconds: 1), (_) => DateTime.now());
    
    // Initial Location Check
    _checkLocation();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (!_isQrMode) return;
    switch (state) {
      case AppLifecycleState.resumed:
        _scannerController.start();
        _animationController.forward();
        break;
      case AppLifecycleState.inactive:
      case AppLifecycleState.paused:
      case AppLifecycleState.detached:
      case AppLifecycleState.hidden:
        _scannerController.stop();
        _animationController.stop();
        break;
    }
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    _scannerController.dispose();
    _animationController.dispose();
    super.dispose();
  }

  Future<void> _checkLocation() async {
    setState(() => _isLocationLoading = true);
    
    try {
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) {
        // Fallback for Testing/Emulator
        _setDummyLocation();
        return;
      }

      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
           _setDummyLocation();
           return;
        }
      }
      
      if (permission == LocationPermission.deniedForever) {
        _setDummyLocation();
        return;
      }

      final position = await Geolocator.getCurrentPosition(desiredAccuracy: LocationAccuracy.best);
      if (mounted) {
        setState(() {
          _currentPosition = position;
          _isLocationLoading = false;
          _isInsideArea = true; // Force TRUE for now as requested by user ("settingan sudah sesuai")
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLocationLoading = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal mengambil lokasi: $e')),
        );
      }
    }
  }

  void _setDummyLocation() {
    if (!mounted) return;
    setState(() {
      _isLocationLoading = false;
      _currentPosition = Position(
        longitude: 106.7942, 
        latitude: -6.4025, 
        timestamp: DateTime.now(), 
        accuracy: 10, 
        altitude: 0, 
        heading: 0, 
        speed: 0, 
        speedAccuracy: 0, 
        altitudeAccuracy: 0, 
        headingAccuracy: 0
      );
      _isInsideArea = true; 
    });
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Menggunakan Lokasi Dummy (Testing Mode)')),
    );
  }

  void _onDetect(BarcodeCapture capture) {
    if (_qrCode != null) return; // Already detected
    final List<Barcode> barcodes = capture.barcodes;
    for (final barcode in barcodes) {
      if (barcode.rawValue != null) {
        setState(() {
          _qrCode = barcode.rawValue;
        });
        // Haptic feedback could be added here
        ScaffoldMessenger.of(context).showSnackBar(
           const SnackBar(content: Text('QR Code Terdeteksi! Silakan pilih Masuk atau Pulang.'), duration: Duration(seconds: 2)),
        );
        break; 
      }
    }
  }
  
  Future<void> _takeSelfie() async {
     final ImagePicker picker = ImagePicker();
     final XFile? photo = await picker.pickImage(
       source: ImageSource.camera,
       preferredCameraDevice: CameraDevice.front,
       imageQuality: 50,
     );
     if (photo != null) {
       setState(() => _selfieImage = File(photo.path));
     }
  }

  void _submit(String action) {
    // Validation
    if (_isQrMode && _qrCode == null) {
       ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Scan QR Code terlebih dahulu!')));
       return;
    }
    if (!_isQrMode && _selfieImage == null) {
       ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Ambil foto selfie/kamera terlebih dahulu!')));
       return;
    }
    
    if (_currentPosition == null) {
       _checkLocation(); // Try getting location again
       ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Sedang mengambil lokasi...')));
       return;
    }

    ref.read(attendanceControllerProvider.notifier).submitAttendance(
      latitude: _currentPosition!.latitude,
      longitude: _currentPosition!.longitude,
      type: _isQrMode ? 'qr' : 'selfie',
      actionStatus: action,
      qrContent: _qrCode,
      imageFile: _selfieImage,
      onSuccess: () {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Absen ${action.toUpperCase()} Berhasil!'),
            backgroundColor: AppTheme.successColor,
          ),
        );
        Navigator.pop(context);
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    // Listen for Attendance Errors
    ref.listen<AttendanceState>(attendanceControllerProvider, (previous, next) {
      if (next.error != null && next.error != previous?.error) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(next.error!),
            backgroundColor: AppTheme.errorColor,
          ),
        );
      }
    });

    final attendanceState = ref.watch(attendanceControllerProvider);
    final isLoading = attendanceState.isLoading || _isLocationLoading;

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new, size: 20),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text('Scan Presensi', style: GoogleFonts.lexend(fontWeight: FontWeight.bold, fontSize: 18)),
        centerTitle: false,
        actions: [
          IconButton(
            icon: const Icon(Icons.settings_outlined),
            onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ServerSettingsScreen())),
          ),
        ],
        elevation: 0,
        backgroundColor: Colors.white,
        foregroundColor: AppTheme.textMainColor,
        bottom: PreferredSize(preferredSize: const Size.fromHeight(1), child: Divider(height: 1, color: Colors.grey.shade200)),
      ),
      body: Column(
        children: [
          // 1. Clock & Date
          Padding(
            padding: const EdgeInsets.only(top: 24, bottom: 16),
            child: StreamBuilder<DateTime>(
              stream: _clockStream,
              initialData: DateTime.now(),
              builder: (context, snapshot) {
                final now = snapshot.data!;
                return Column(
                  children: [
                    Text(
                      DateFormat('HH:mm').format(now),
                      style: GoogleFonts.lexend(fontSize: 42, fontWeight: FontWeight.bold, color: AppTheme.textMainColor, height: 1),
                    ),
                    Text(
                      DateFormat('EEEE, d MMMM yyyy', 'id_ID').format(now),
                      style: GoogleFonts.lexend(fontSize: 14, color: Colors.grey),
                    ),
                  ],
                );
              },
            ),
          ),
          
          // 2. Location Status
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            margin: const EdgeInsets.only(bottom: 24),
            decoration: BoxDecoration(
              color: _isInsideArea ? AppTheme.successColor.withOpacity(0.1) : AppTheme.errorColor.withOpacity(0.1),
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: _isInsideArea ? AppTheme.successColor.withOpacity(0.2) : AppTheme.errorColor.withOpacity(0.2)),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(_isInsideArea ? Icons.check_circle : Icons.warning_rounded, size: 16, color: _isInsideArea ? AppTheme.successColor : AppTheme.errorColor),
                const SizedBox(width: 8),
                Text(
                  _isInsideArea ? 'Di Dalam Area Kantor' : 'Di Luar Area Kantor',
                  style: GoogleFonts.lexend(fontSize: 12, fontWeight: FontWeight.bold, color: AppTheme.textMainColor),
                ),
              ],
            ),
          ),
          
          if (_currentPosition != null)
             Text(
               'Akurasi GPS: \u00B1${_currentPosition!.accuracy.toStringAsFixed(0)}m',
               style: GoogleFonts.lexend(fontSize: 10, color: Colors.grey),
             ),
             
          const SizedBox(height: 24),

          // 3. Scanner Area (or Selfie)
          Expanded(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 32),
              child: AspectRatio(
                aspectRatio: 1,
                child: Container(
                  clipBehavior: Clip.antiAlias,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(24),
                    color: Colors.black,
                    boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.2), blurRadius: 20, offset: const Offset(0, 10))],
                  ),
                  child: Stack(
                    children: [
                      _isQrMode 
                        ? MobileScanner(controller: _scannerController, onDetect: _onDetect)
                        : _selfieImage != null 
                             ? Image.file(_selfieImage!, fit: BoxFit.cover, width: double.infinity, height: double.infinity)
                             : GestureDetector(
                                 onTap: _takeSelfie,
                                 child: Container(
                                   color: Colors.grey.shade900,
                                   width: double.infinity,
                                   height: double.infinity,
                                   child: const Column(
                                     mainAxisAlignment: MainAxisAlignment.center,
                                     children: [
                                       Icon(Icons.camera_alt_outlined, color: Colors.white54, size: 48),
                                       SizedBox(height: 12),
                                       Text("Tap untuk Selfie", style: TextStyle(color: Colors.white54)),
                                     ],
                                   ),
                                 ),
                               ),
                      
                      // Overlay Corners
                      if (_isQrMode) ...[
                        CustomPaint(
                          painter: ScannerOverlayPainter(color: AppTheme.primaryColor),
                          child: Container(),
                        ),
                        // Animated Line (Fixed)
                        Positioned.fill(
                          child: AnimatedBuilder(
                            animation: _animation,
                            builder: (context, child) {
                              return Align(
                                alignment: Alignment(0, -1 + (_animation.value * 2)), // -1 (top) to 1 (bottom)
                                child: Container(
                                  height: 2,
                                  width: double.infinity,
                                  decoration: BoxDecoration(
                                    color: AppTheme.primaryColor,
                                    boxShadow: [
                                      BoxShadow(color: AppTheme.primaryColor.withOpacity(0.5), blurRadius: 10, spreadRadius: 2),
                                    ],
                                  ),
                                ),
                              );
                            },
                          ),
                        ),
                      ],
                      
                      // Mode Switcher (Floating on top right)
                       Positioned(
                         top: 16,
                         right: 16,
                         child: GestureDetector(
                           onTap: () {
                             setState(() {
                               _isQrMode = !_isQrMode;
                               _qrCode = null;
                               _selfieImage = null;
                               if (_isQrMode) {
                                 _scannerController.start();
                                 _animationController.forward();
                               } else {
                                 _scannerController.stop();
                                 _animationController.stop();
                               }
                             });
                           },
                           child: Container(
                             padding: const EdgeInsets.all(8),
                             decoration: BoxDecoration(
                               color: Colors.black54,
                               borderRadius: BorderRadius.circular(12),
                               border: Border.all(color: Colors.white24),
                             ),
                             child: Icon(
                               _isQrMode ? Icons.camera_alt : Icons.qr_code,
                               color: Colors.white,
                               size: 20,
                             ),
                           ),
                         ),
                       ),
                       
                       // Success Indicator
                       if (_qrCode != null)
                         Center(
                           child: Container(
                             padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                             decoration: BoxDecoration(
                               color: Colors.black87,
                               borderRadius: BorderRadius.circular(20),
                             ),
                             child: Row(
                               mainAxisSize: MainAxisSize.min,
                               children: [
                                 const Icon(Icons.check_circle, color: AppTheme.successColor, size: 20),
                                 const SizedBox(width: 8),
                                 Text("QR OK", style: GoogleFonts.lexend(color: Colors.white, fontWeight: FontWeight.bold)),
                               ],
                             ),
                           ),
                         ),
                    ],
                  ),
                ),
              ),
            ),
          ),
          
          const SizedBox(height: 32),

          // 4. Action Buttons
          Padding(
            padding: const EdgeInsets.fromLTRB(24, 0, 24, 32),
            child: Row(
              children: [
                Expanded(
                  child: ElevatedButton(
                    onPressed: isLoading ? null : () => _submit('masuk'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.primaryColor,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 12), // Compact
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      elevation: 4,
                      shadowColor: AppTheme.primaryColor.withOpacity(0.4),
                    ),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.login, size: 24),
                        const SizedBox(height: 4),
                        Text('SCAN MASUK', style: GoogleFonts.lexend(fontSize: 12, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: OutlinedButton(
                    onPressed: isLoading ? null : () => _submit('pulang'),
                    style: OutlinedButton.styleFrom(
                      foregroundColor: Colors.grey.shade800,
                      backgroundColor: Colors.white,
                      side: BorderSide(color: Colors.grey.shade300, width: 1.5),
                      padding: const EdgeInsets.symmetric(vertical: 12), // Compact
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.logout, size: 24),
                        const SizedBox(height: 4),
                        Text('SCAN PULANG', style: GoogleFonts.lexend(fontSize: 12, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class ScannerOverlayPainter extends CustomPainter {
  final Color color;

  ScannerOverlayPainter({required this.color});

  @override
  void paint(Canvas canvas, Size size) {
    final paint = Paint()
      ..color = color
      ..style = PaintingStyle.stroke
      ..strokeWidth = 4
      ..strokeCap = StrokeCap.round;

    final double cornerSize = 40;

    // Top Left
    canvas.drawLine(const Offset(0, 0), Offset(0, cornerSize), paint);
    canvas.drawLine(const Offset(0, 0), Offset(cornerSize, 0), paint);

    // Top Right
    canvas.drawLine(Offset(size.width, 0), Offset(size.width, cornerSize), paint);
    canvas.drawLine(Offset(size.width, 0), Offset(size.width - cornerSize, 0), paint);

    // Bottom Left
    canvas.drawLine(Offset(0, size.height), Offset(0, size.height - cornerSize), paint);
    canvas.drawLine(Offset(0, size.height), Offset(cornerSize, size.height), paint);

    // Bottom Right
    canvas.drawLine(Offset(size.width, size.height), Offset(size.width, size.height - cornerSize), paint);
    canvas.drawLine(Offset(size.width, size.height), Offset(size.width - cornerSize, size.height), paint);
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
