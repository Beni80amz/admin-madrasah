# Aplikasi Mobile Absensi Digital

## Ringkasan
Aplikasi mobile Flutter untuk sistem absensi Madrasah Ibtidaiyah Al-Amsar. Mendukung akun untuk Guru dan Siswa.

## Struktur Project
- `lib/core`: Konstanta, Tema, Network, Logika Offline.
- `lib/features`: Auth (Login), Absensi, Izin, Riwayat, Profil.

## Instruksi Instalasi (Setelah Flutter SDK terinstall)

1. **Inisialisasi Project**
   Karena struktur project dibuat secara manual, jalankan perintah berikut untuk menghasilkan file platform (Android/iOS):
   ```bash
   cd mobile
   flutter create .
   ```

2. **Install Dependencies**
   ```bash
   flutter pub get
   ```

3. **Konfigurasi Android**
   Buka `android/app/src/main/AndroidManifest.xml` dan tambahkan izin berikut di dalam tag `<manifest>`:
   ```xml
   <uses-permission android:name="android.permission.INTERNET"/>
   <uses-permission android:name="android.permission.CAMERA"/>
   <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION"/>
   <uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION"/>
   ```

   **Versi MinSdk:**
   Update `android/app/build.gradle`:
   ```gradle
   defaultConfig {
       minSdkVersion 24
       // ...
   }
   ```

4. **Konfigurasi iOS**
   Buka `ios/Runner/Info.plist` dan tambahkan:
   ```xml
   <key>NSCameraUsageDescription</key>
   <string>Kamera diperlukan untuk absensi selfie dan scan QR.</string>
   <key>NSLocationWhenInUseUsageDescription</key>
   <string>Lokasi diperlukan untuk memverifikasi posisi absensi.</string>
   <key>NSPhotoLibraryUsageDescription</key>
   <string>Akses galeri foto diperlukan untuk mengunggah lampiran izin.</string>
   ```

5. **Jalankan Aplikasi**
   ```bash
   flutter run
   ```

## Mode Offline
Aplikasi mendukung pengiriman absensi secara offline. Permintaan akan antre (queue) di penyimpanan lokal (Hive) dan disinkronkan saat online.

## Backend
Pastikan backend API berjalan dan dapat diakses. Update `lib/core/constants/api_constants.dart` jika URL berubah.
Base URL Saat Ini: `https://miamzdepok.sch.id`
