import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import '../leave_controller.dart';
import '../../../../core/theme/app_theme.dart';

class LeaveRequestScreen extends ConsumerStatefulWidget {
  const LeaveRequestScreen({super.key});

  @override
  ConsumerState<LeaveRequestScreen> createState() => _LeaveRequestScreenState();
}

class _LeaveRequestScreenState extends ConsumerState<LeaveRequestScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final _formKey = GlobalKey<FormState>();
  
  String _selectedType = 'sakit';
  DateTime? _startDate;
  DateTime? _endDate;
  final _reasonController = TextEditingController();
  File? _attachment;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    WidgetsBinding.instance.addPostFrameCallback((_) {
      ref.read(leaveControllerProvider.notifier).loadRequests();
    });
  }

  @override
  void dispose() {
    _tabController.dispose();
    _reasonController.dispose();
    super.dispose();
  }
  
  Future<void> _pickDate(bool isStart) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2020),
      lastDate: DateTime(2030),
    );
    
    if (picked != null) {
      setState(() {
        if (isStart) {
          _startDate = picked;
          // Auto set end date if not set or before start
          if (_endDate == null || _endDate!.isBefore(_startDate!)) {
            _endDate = picked;
          }
        } else {
          _endDate = picked;
        }
      });
    }
  }
  
  Future<void> _pickAttachment() async {
    final ImagePicker picker = ImagePicker();
    showModalBottomSheet(
      context: context,
      builder: (context) => SafeArea(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            ListTile(
              leading: const Icon(Icons.camera_alt),
              title: const Text('Kamera'),
              onTap: () async {
                Navigator.pop(context);
                final XFile? photo = await picker.pickImage(source: ImageSource.camera);
                if (photo != null) setState(() => _attachment = File(photo.path));
              },
            ),
            ListTile(
              leading: const Icon(Icons.image),
              title: const Text('Galeri'),
              onTap: () async {
                Navigator.pop(context);
                final XFile? photo = await picker.pickImage(source: ImageSource.gallery);
                if (photo != null) setState(() => _attachment = File(photo.path));
              },
            ),
          ],
        ),
      ),
    );
  }

  void _submit() {
    if (_formKey.currentState!.validate()) {
      if (_startDate == null || _endDate == null) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Pilih tanggal mulai dan selesai')));
        return;
      }
      
      ref.read(leaveControllerProvider.notifier).submitRequest(
        type: _selectedType,
        startDate: _startDate!,
        endDate: _endDate!,
        reason: _reasonController.text,
        attachment: _attachment,
        onSuccess: () {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Pengajuan berhasil dikirim'), backgroundColor: AppTheme.successColor),
          );
          // Reset form
          setState(() {
            _reasonController.clear();
            _attachment = null;
            _startDate = null;
            _endDate = null;
          });
          // Switch to history tab
          _tabController.animateTo(1);
        },
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Izin & Sakit', style: GoogleFonts.lexend()),
        bottom: TabBar(
          controller: _tabController,
          labelStyle: GoogleFonts.lexend(fontWeight: FontWeight.bold),
          indicatorColor: AppTheme.primaryColor,
          labelColor: AppTheme.primaryColor,
          tabs: const [
            Tab(text: 'Ajukan Baru'),
            Tab(text: 'Riwayat'),
          ],
        ),
      ),
      body: TabBarView(
        controller: _tabController,
        children: [
          _buildForm(),
          _buildHistory(),
        ],
      ),
    );
  }

  Widget _buildForm() {
    final leaveState = ref.watch(leaveControllerProvider);
    
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            DropdownButtonFormField<String>(
              value: _selectedType,
              decoration: const InputDecoration(labelText: 'Jenis Izin'),
              items: const [
                DropdownMenuItem(value: 'sakit', child: Text('Sakit')),
                DropdownMenuItem(value: 'izin', child: Text('Izin')),
              ],
              onChanged: (val) => setState(() => _selectedType = val!),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: InkWell(
                    onTap: () => _pickDate(true),
                    child: InputDecorator(
                      decoration: const InputDecoration(
                        labelText: 'Dari Tanggal',
                        suffixIcon: Icon(Icons.calendar_today),
                      ),
                      child: Text(
                        _startDate != null ? DateFormat('dd/MM/yyyy').format(_startDate!) : 'Pilih Tanggal',
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: InkWell(
                    onTap: () => _pickDate(false),
                    child: InputDecorator(
                      decoration: const InputDecoration(
                        labelText: 'Sampai Tanggal',
                        suffixIcon: Icon(Icons.calendar_today),
                      ),
                      child: Text(
                        _endDate != null ? DateFormat('dd/MM/yyyy').format(_endDate!) : 'Pilih Tanggal',
                      ),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            TextFormField(
              controller: _reasonController,
              maxLines: 3,
              decoration: const InputDecoration(labelText: 'Alasan / Keterangan'),
              validator: (val) => val == null || val.isEmpty ? 'Keterangan wajib diisi' : null,
            ),
            const SizedBox(height: 16),
            InkWell(
              onTap: _pickAttachment,
              child: Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  border: Border.all(color: Colors.grey),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.attach_file, color: Colors.grey),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        _attachment != null ? _attachment!.path.split('/').last : 'Lampiran (Surat Dokter/Lainnya)',
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: leaveState.isLoading ? null : _submit,
                child: leaveState.isLoading
                   ? const CircularProgressIndicator(color: Colors.white)
                   : const Text('Kirim Pengajuan'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHistory() {
    final leaveState = ref.watch(leaveControllerProvider);
    
    if (leaveState.isLoading && leaveState.requests.isEmpty) {
      return const Center(child: CircularProgressIndicator());
    }
    
    if (leaveState.requests.isEmpty) {
       return const Center(child: Text('Belum ada riwayat pengajuan'));
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: leaveState.requests.length,
      itemBuilder: (context, index) {
        final item = leaveState.requests[index];
        Color statusColor = Colors.orange;
        if (item['status'] == 'approved') statusColor = Colors.green;
        if (item['status'] == 'rejected') statusColor = Colors.red;

        return Card(
          elevation: 2,
          margin: const EdgeInsets.only(bottom: 12),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          child: ListTile(
            leading: CircleAvatar(
               backgroundColor: statusColor.withOpacity(0.1),
               child: Icon(
                 item['type'] == 'sakit' ? Icons.medical_services : Icons.description,
                 color: statusColor,
               ),
            ),
            title: Text(
               item['type'].toString().toUpperCase(),
               style: GoogleFonts.lexend(fontWeight: FontWeight.bold),
            ),
            subtitle: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('${DateFormat('dd MMM').format(DateTime.parse(item['start_date']))} - ${DateFormat('dd MMM yyyy').format(DateTime.parse(item['end_date']))}'),
                Text(item['reason'] ?? '', maxLines: 1, overflow: TextOverflow.ellipsis),
              ],
            ),
            trailing: Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: statusColor,
                borderRadius: BorderRadius.circular(8),
              ),
              child: Text(
                item['status'].toString().toUpperCase(),
                style: GoogleFonts.lexend(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
              ),
            ),
          ),
        );
      },
    );
  }
}
