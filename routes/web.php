<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Home;

Route::get('/', Home::class)->name('home');
Route::get('/kontak', App\Livewire\Contact::class)->name('contact');
Route::get('/galeri', App\Livewire\Gallery::class)->name('gallery');
Route::get('/profil', App\Livewire\Profile::class)->name('profile');
Route::get('/berita', App\Livewire\News::class)->name('news');
Route::get('/berita/{slug}', App\Livewire\NewsShow::class)->name('news.show');

// Akademik
Route::get('/akademik/kurikulum', App\Livewire\Curriculum::class)->name('akademik.kurikulum');
Route::get('/akademik/kurikulum/download-pdf', [App\Http\Controllers\StrukturKurikulumController::class, 'downloadPdf'])->name('akademik.kurikulum.download');
Route::get('/akademik/kalender', App\Livewire\AcademicCalendar::class)->name('akademik.kalender');
Route::get('/akademik/kalender/download-pdf', [App\Http\Controllers\AcademicCalendarController::class, 'downloadPdf'])->name('akademik.kalender.download');
Route::get('/akademik/kalender/download-visual-pdf', [App\Http\Controllers\VisualCalendarController::class, 'downloadPdf'])->name('akademik.kalender.download-visual');
Route::get('/profil/struktur-organisasi/download-pdf', [App\Http\Controllers\StrukturOrganisasiController::class, 'downloadPdf'])->name('profil.struktur-organisasi.download');
Route::get('/profil/download-pdf', [App\Http\Controllers\ProfilMadrasahController::class, 'downloadPdf'])->name('profil.download');
Route::get('/profil/verifikasi', App\Livewire\VerifikasiProfil::class)->name('profil.verifikasi');
Route::get('/akademik/prestasi-siswa', App\Livewire\StudentAchievements::class)->name('akademik.prestasi-siswa');
Route::get('/akademik/prestasi-siswa/download-pdf', [App\Http\Controllers\AchievementController::class, 'downloadStudentPdf'])->name('akademik.prestasi-siswa.download');
Route::get('/akademik/prestasi-guru', App\Livewire\TeacherAchievements::class)->name('akademik.prestasi-guru');
Route::get('/akademik/prestasi-guru/download-pdf', [App\Http\Controllers\AchievementController::class, 'downloadTeacherPdf'])->name('akademik.prestasi-guru.download');
Route::get('/akademik/data-siswa', App\Livewire\StudentData::class)->name('akademik.data-siswa');
Route::get('/akademik/data-siswa/download-pdf', [App\Http\Controllers\StudentController::class, 'downloadPdf'])->name('akademik.data-siswa.download');
Route::get('/akademik/data-guru', App\Livewire\TeacherData::class)->name('akademik.data-guru');
Route::get('/akademik/data-alumni', App\Livewire\AlumniData::class)->name('akademik.data-alumni');
Route::get('/akademik/data-alumni/download-pdf', [App\Http\Controllers\AlumniController::class, 'downloadPdf'])->name('akademik.data-alumni.download');

// Surat Keterangan Pindah/Keluar
Route::get('/siswa-keluar/{id}/surat-pindah', [App\Http\Controllers\SuratPindahController::class, 'download'])->name('siswa-keluar.surat-pindah');
Route::get('/siswa-keluar/{id}/surat-pindah/preview', [App\Http\Controllers\SuratPindahController::class, 'stream'])->name('siswa-keluar.surat-pindah.preview');
Route::get('/verifikasi-surat/{id}', [App\Http\Controllers\VerifikasiSuratController::class, 'show'])->name('verifikasi-surat');

// Surat Penerimaan Siswa Masuk
Route::get('/siswa-masuk/{id}/surat-penerimaan', [App\Http\Controllers\SuratPenerimaanController::class, 'download'])->name('siswa-masuk.surat-penerimaan');
Route::get('/siswa-masuk/{id}/surat-penerimaan/preview', [App\Http\Controllers\SuratPenerimaanController::class, 'stream'])->name('siswa-masuk.surat-penerimaan.preview');
Route::get('/verifikasi-surat-masuk/{id}', [App\Http\Controllers\VerifikasiSuratMasukController::class, 'show'])->name('verifikasi-surat-masuk');


// Unauthorized Access
Route::get('/akses-terbatas', App\Livewire\Unauthorized::class)->name('unauthorized');

// PPDB (Penerimaan Peserta Didik Baru)
Route::get('/ppdb', App\Livewire\Ppdb\Ppdb::class)->name('ppdb');
Route::get('/ppdb/daftar', App\Livewire\Ppdb\Register::class)->name('ppdb.register');
Route::get('/ppdb/sukses/{id}', App\Livewire\Ppdb\Success::class)->name('ppdb.success');
Route::get('/ppdb/receipt/{id}/download', [App\Http\Controllers\PpdbReceiptController::class, 'download'])->name('ppdb.receipt.download');
Route::get('/ppdb/receipt/{id}/preview', [App\Http\Controllers\PpdbReceiptController::class, 'stream'])->name('ppdb.receipt.preview');

// Frontend Authentication
Route::get('/login', [App\Http\Controllers\Auth\CustomAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\CustomAuthController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\Auth\CustomAuthController::class, 'logout'])->name('logout');



// Public Monitor Routes
Route::get('/scan/monitor', [App\Http\Controllers\AttendanceController::class, 'monitor'])->name('scan.monitor');
Route::get('/scan/generate-qr', [App\Http\Controllers\AttendanceController::class, 'generateQr'])->name('attendance.generate-qr');

// Frontend Dashboard & Attendance
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/scan', [App\Http\Controllers\AttendanceController::class, 'index'])->name('scan.index');
    Route::post('/scan/store', [App\Http\Controllers\AttendanceController::class, 'store'])->name('scan.store');

    // Placeholder Routes for Menu Items
    // Izin & Sakit (Leave Requests)
    Route::prefix('izin')->name('leave.')->group(function () {
        Route::get('/', [App\Http\Controllers\LeaveRequestController::class, 'index'])->name('index');
        Route::get('/buat', [App\Http\Controllers\LeaveRequestController::class, 'create'])->name('create');
        Route::post('/simpan', [App\Http\Controllers\LeaveRequestController::class, 'store'])->name('store');
        Route::post('/{leaveRequest}/approve', [App\Http\Controllers\LeaveRequestController::class, 'approve'])->name('approve');
        Route::post('/{leaveRequest}/reject', [App\Http\Controllers\LeaveRequestController::class, 'reject'])->name('reject');
    });

    Route::get('/riwayat', [App\Http\Controllers\AttendanceController::class, 'history'])->name('riwayat.index');
    Route::get('/riwayat/export', [App\Http\Controllers\AttendanceController::class, 'downloadPdf'])->name('riwayat.export');
    Route::get('/verifikasi-absensi', [App\Http\Controllers\AttendanceController::class, 'verify'])->name('attendance.verify');

    Route::view('/jadwal', 'frontend.features.jadwal')->name('jadwal.index');
    Route::view('/tugas', 'frontend.features.tugas')->name('tugas.index');
    Route::view('/inbox', 'frontend.features.inbox')->name('inbox.index');
    Route::view('/profil-user', 'frontend.features.profil')->name('profil.user');
});

// TEMPORARY DEBUG ROUTE
// TEMPORARY DEBUG ROUTE
Route::get('/debug-ppdb', function () {
    try {
        $raw = \App\Models\AppSetting::where('key', 'ppdb_persyaratan')->first();
        $processed = \App\Models\AppSetting::getPpdbPersyaratan();

        return response()->json([
            'status' => 'Debug Info',
            'raw_db_value' => $raw ? $raw->value : 'NULL',
            'processed_result' => $processed,
            'php_version' => phpversion(),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
