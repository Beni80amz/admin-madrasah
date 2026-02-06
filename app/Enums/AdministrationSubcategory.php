<?php

namespace App\Enums;

enum AdministrationSubcategory: string
{
    // Perencanaan Pembelajaran
    case KALDIK = 'kaldik';
    case PROTA = 'prota';
    case PROSEM = 'prosem';
    case CP = 'capaian_pembelajaran';
    case TP = 'tujuan_pembelajaran';
    case ATP = 'alur_tujuan_pembelajaran';
    case RPP = 'rpp';
    case PPE = 'pekan_efektif';
    case KKTP = 'kktp';
    case PERENCANAAN_LAINNYA = 'perencanaan_lainnya';

    // Pelaksanaan & Evaluasi
    case JURNAL = 'jurnal_mengajar';
    case ABSENSI = 'absensi_siswa';
    case NILAI = 'daftar_nilai';
    case BANK_SOAL = 'bank_soal';
    case ANALISIS = 'analisis_evaluasi';
    case REMEDIAL = 'remedial_pengayaan';
    case PELAKSANAAN_LAINNYA = 'pelaksanaan_lainnya';

    // Administrasi Pendukung
    case BUKU_PEGANGAN = 'buku_pegangan';
    case CATATAN_KASUS = 'catatan_kasus';
    case INVENTARIS = 'inventaris_kelas';
    case NOTULEN = 'notulen_rapat';
    case PENDUKUNG_LAINNYA = 'pendukung_lainnya';

    public function label(): string
    {
        return match ($this) {
                // Perencanaan
            self::KALDIK => 'Kalender Pendidikan (Kaldik)',
            self::PROTA => 'Program Tahunan (Prota)',
            self::PROSEM => 'Program Semester (Prosem)',
            self::CP => 'Capaian Pembelajaran (CP)',
            self::TP => 'Tujuan Pembelajaran (TP)',
            self::ATP => 'Alur Tujuan Pembelajaran (ATP)',
            self::RPP => 'RPP / Modul Ajar',
            self::PPE => 'Perhitungan Pekan Efektif (PPE)',
            self::KKTP => 'Kriteria Ketercapaian Tujuan Pembelajaran (KKTP)',
            self::PERENCANAAN_LAINNYA => 'Lainnya (Perencanaan)',

                // Pelaksanaan
            self::JURNAL => 'Jurnal Agenda Mengajar',
            self::ABSENSI => 'Buku Absensi/Presensi Siswa',
            self::NILAI => 'Daftar Nilai Siswa',
            self::BANK_SOAL => 'Bank Soal: Kisi-kisi dan Soal',
            self::ANALISIS => 'Analisis Hasil Evaluasi/Ulangan',
            self::REMEDIAL => 'Program Remedial dan Pengayaan',
            self::PELAKSANAAN_LAINNYA => 'Lainnya (Pelaksanaan)',

                // Pendukung
            self::BUKU_PEGANGAN => 'Buku Pegangan Guru/Siswa (Modul/LKS)',
            self::CATATAN_KASUS => 'Buku Catatan Kasus/Psikologi Siswa',
            self::INVENTARIS => 'Daftar Inventaris Kelas',
            self::NOTULEN => 'Notulen Rapat',
            self::PENDUKUNG_LAINNYA => 'Lainnya (Pendukung)',
        };
    }

    public function category(): AdministrationCategory
    {
        return match ($this) {
            self::KALDIK, self::PROTA, self::PROSEM, self::CP, self::TP,
            self::ATP, self::RPP, self::PPE, self::KKTP, self::PERENCANAAN_LAINNYA
            => AdministrationCategory::PERENCANAAN,

            self::JURNAL, self::ABSENSI, self::NILAI, self::BANK_SOAL,
            self::ANALISIS, self::REMEDIAL, self::PELAKSANAAN_LAINNYA
            => AdministrationCategory::PELAKSANAAN,

            self::BUKU_PEGANGAN, self::CATATAN_KASUS, self::INVENTARIS,
            self::NOTULEN, self::PENDUKUNG_LAINNYA
            => AdministrationCategory::PENDUKUNG,
        };
    }

    public static function forCategory(AdministrationCategory $category): array
    {
        return collect(self::cases())
            ->filter(fn($case) => $case->category() === $category)
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }

    public static function options(): array
    {
        $grouped = [];
        foreach (AdministrationCategory::cases() as $category) {
            $grouped[$category->label()] = self::forCategory($category);
        }
        return $grouped;
    }

    public static function flatOptions(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }
}
