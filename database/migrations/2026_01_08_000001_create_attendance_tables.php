<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Attendances Table
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->string('status')->default('alpha'); // hadir, telat, izin, sakit, alpha
            $table->integer('keterlambatan')->default(0); // minutes
            $table->integer('lembur')->default(0); // minutes
            $table->text('lat_in')->nullable();
            $table->text('long_in')->nullable();
            $table->string('photo_in')->nullable();
            $table->text('lat_out')->nullable();
            $table->text('long_out')->nullable();
            $table->string('photo_out')->nullable();
            $table->string('device_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            // Index for faster queries
            $table->index(['user_id', 'date']);
            $table->index('date');
        });

        // 2. Create Leave Requests Table
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // sakit, izin
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->string('attachment')->nullable(); // photo surat dokter dll
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Create Attendance Settings Table
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 4. Add user_id to students and teachers if not exists
        // Note: We need to check first because sometimes these might exist depending on previous devs

        if (!Schema::hasColumn('students', 'user_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('teachers', 'user_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        // Seed default settings
        DB::table('attendance_settings')->insert([
            ['key' => 'office_lat', 'value' => '-6.200000', 'description' => 'Latitude Lokasi Kantor/Sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'office_long', 'value' => '106.816666', 'description' => 'Longitude Lokasi Kantor/Sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'radius_meter', 'value' => '100', 'description' => 'Radius Jangkauan Absensi (Meter)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'work_start_time', 'value' => '07:00:00', 'description' => 'Jam Masuk Kerja/Sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'work_end_time', 'value' => '16:00:00', 'description' => 'Jam Pulang Kerja/Sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'late_tolerance_minutes', 'value' => '15', 'description' => 'Toleransi Keterlambatan (Menit)', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('teachers', 'user_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('students', 'user_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        Schema::dropIfExists('attendance_settings');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('attendances');
    }
};
