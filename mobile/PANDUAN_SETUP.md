# Panduan Lengkap Setup & Menjalankan Aplikasi

Berikut adalah langkah-langkah detail yang perlu Anda lakukan agar aplikasi ini bisa berjalan di komputer Anda.

## Langkah 1: Mendaftarkan Flutter ke Windows (Environment Variable)

Langkah ini dilakukan agar perintah `flutter` bisa dikenali di semua terminal.

1.  Tekan tombol **Windows** di keyboard Anda.
2.  Ketik kata kunci: `env` atau `environment`.
3.  Akan muncul pilihan **"Edit the system environment variables"**, klik pilihan tersebut.
4.  Pada jendela kecil yang muncul, klik tombol **"Environment Variables..."** di bagian kanan bawah.
5.  Akan muncul dua kolom. Lihat kolom bagian **atas** ("User variables for [User]").
6.  Cari tulisan **"Path"** di kolom tersebut, klik sekali untuk memilihnya, lalu klik tombol **"Edit..."**.
7.  Akan muncul daftar path. Klik tombol **"New"** di sebelah kanan.
8.  Ketik (atau copy-paste) tulisan ini:
    `D:\flutter\flutter\bin`
9.  Klik **OK**.
10. Klik **OK** lagi di jendela sebelumnya.
11. Klik **OK** lagi untuk menutup semua jendela.

**Cara Cek Berhasil:**
- Buka terminal baru (PowerShell atau CMD).
- Ketik `flutter --version`.
- Jika muncul tulisan versi Flutter, berarti **BERHASIL**.

---

## Langkah 2: Menyiapkan Project Mobile

1.  Buka terminal (bisa lewat VS Code atau Terminal biasa).
2.  Masuk ke folder project mobile:
    ```powershell
    cd d:\laragon\www\admin-madrasah\mobile
    ```
3.  Jalankan perintah ini untuk mendownload semua library yang dibutuhkan:
    ```powershell
    flutter pub get
    ```
    *(Tunggu sampai proses selesai)*

---

## Langkah 3: Menghubungkan HP Android (PENTING!)

Agar aplikasi bisa muncul di HP Anda, Anda harus mengaktifkan **"USB Debugging"**.

1.  **Aktifkan Developer Options:**
    *   Buka **Settings (Pengaturan)** di HP.
    *   Cari menu **About Phone (Tentang Ponsel)**.
    *   Cari tulisan **Build Number** (Nomor Bentukan).
    *   Ketuk (tap) tulisan Build Number itu sebanyak **7 kali** dengan cepat sampai muncul tulisan "You are now a developer!".
2.  **Aktifkan USB Debugging:**
    *   Kembali ke menu utama Settings.
    *   Cari menu **Developer Options** (Opsi Pengembang). Biasanya ada di dalam menu *System* atau *Additional Settings*.
    *   Cari tombol **USB Debugging** dan nyalakan (ON).
3.  **Hubungkan Kabel:**
    *   Colokkan kabel USB dari HP ke Laptop/Komputer.
    *   Lihat layar HP. Jika muncul pertanyaan "Allow USB Debugging?", pilih **Allow** (Izinkan).

**Cara Cek Apakah HP Terbaca:**
Di terminal VS Code (di folder `mobile`), ketik:
```powershell
flutter devices
```
Jika muncul nama HP Anda, berarti sudah siap!

---

## Langkah 4: Menjalankan Aplikasi

1.  Pastikan HP sudah tersambung (Langkah 3).
2.  Di terminal folder `mobile`, jalankan:
    ```powershell
    flutter run
    ```
3.  Tunggu proses "Building..." (2-5 menit).
4.  Aplikasi akan terbuka otomatis di HP Anda.

---

## Cara Menggunakan Fitur

### 1. Login
- **Email:** `admin@madrasah.sch.id`
- **Password:** `@#Adm!nM@drasah@#`
- Klik tombol **Login**.

### 2. Absensi (Scan)
- Di menu utama, klik kotak **"Scan Absen"**.
- Izinkan akses kamera jika diminta.
- Arahkan kamera ke **QR Code**.
    - *Cara buat QR Code:* Login ke web Admin Panel -> Menu "Monitor" -> Klik tombol "Toggle QR".
- Atau gunakan tombol **Kamera** di pojok kanan atas untuk absensi Selfie.

### 3. Mode Offline (Tes)
- Matikan WiFi/Data di HP Anda.
- Lakukan Absensi (QR atau Selfie).
- Aplikasi akan menyimpan data di HP.
- Nyalakan kembali WiFi/Data.
- Data akan otomatis dikirim ke server (Sinkronisasi).

---

## Jika Ada Masalah (Troubleshooting)

**Masalah:** "Command 'flutter' not found"
**Solusi:** Ulangi **Langkah 1**. Pastikan path `D:\flutter\flutter\bin` sudah benar. Anda mungkin perlu me-restart komputer/VS Code agar efeknya terasa.

**Masalah:** Aplikasi error saat dijalankan (Error Gradle/Android).
**Solusi:** Coba jalankan perintah perbaikan ini di dalam folder `mobile`:
```powershell
flutter clean
flutter pub get
flutter run
```
