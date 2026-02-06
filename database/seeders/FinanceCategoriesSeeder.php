<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use App\Models\IncomeCategory;
use Illuminate\Database\Seeder;

class FinanceCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategori Pemasukan
        $incomeCategories = [
            ['name' => 'Madrasah Pay', 'description' => 'Pemasukan dari pembayaran siswa via Madrasah Pay'],
            ['name' => 'Donasi/Infaq', 'description' => 'Donasi dan infaq dari masyarakat'],
            ['name' => 'Bantuan Pemerintah', 'description' => 'Bantuan BOS dan bantuan pemerintah lainnya'],
            ['name' => 'Sumbangan Wali Santri', 'description' => 'Sumbangan sukarela dari wali santri'],
            ['name' => 'Hasil Koperasi/Usaha', 'description' => 'Pendapatan dari koperasi dan unit usaha madrasah'],
            ['name' => 'Lain-lain', 'description' => 'Pemasukan lain yang tidak termasuk kategori di atas'],
        ];

        foreach ($incomeCategories as $category) {
            IncomeCategory::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description'], 'is_active' => true]
            );
        }

        // Kategori Pengeluaran
        $expenseCategories = [
            ['name' => 'Gaji Guru/Karyawan', 'description' => 'Pengeluaran untuk gaji dan tunjangan guru/karyawan'],
            ['name' => 'Operasional Harian', 'description' => 'Biaya listrik, air, internet, ATK, dll'],
            ['name' => 'Pemeliharaan Gedung', 'description' => 'Biaya perbaikan dan perawatan gedung'],
            ['name' => 'Pengadaan Alat/Bahan', 'description' => 'Pembelian alat pembelajaran dan bahan habis pakai'],
            ['name' => 'Kegiatan Pendidikan', 'description' => 'Biaya kegiatan belajar mengajar dan ekstrakurikuler'],
            ['name' => 'Konsumsi', 'description' => 'Biaya konsumsi rapat, acara, dll'],
            ['name' => 'Transport', 'description' => 'Biaya transportasi dan perjalanan dinas'],
            ['name' => 'Lain-lain', 'description' => 'Pengeluaran lain yang tidak termasuk kategori di atas'],
        ];

        foreach ($expenseCategories as $category) {
            ExpenseCategory::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description'], 'is_active' => true]
            );
        }

        $this->command->info('Finance categories seeded successfully!');
    }
}
