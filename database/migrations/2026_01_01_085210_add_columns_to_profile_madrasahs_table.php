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
        Schema::table('profile_madrasahs', function (Blueprint $table) {
            $columns = [
                'motto' => ['type' => 'string', 'after' => 'nama_madrasah'],
                'email' => ['type' => 'string', 'after' => 'alamat'],
                'no_hp' => ['type' => 'string', 'after' => 'email'],
                'whatsapp' => ['type' => 'string', 'after' => 'no_hp'],
                'facebook' => ['type' => 'string', 'after' => 'whatsapp'],
                'instagram' => ['type' => 'string', 'after' => 'facebook'],
                'youtube' => ['type' => 'string', 'after' => 'instagram'],
                'google_maps_embed' => ['type' => 'text', 'after' => 'youtube'],
                'visi' => ['type' => 'longText', 'after' => 'sejarah_singkat'],
                'misi' => ['type' => 'longText', 'after' => 'visi'],
                'nama_kepala_madrasah' => ['type' => 'string', 'after' => 'misi'],
                'nip_kepala_madrasah' => ['type' => 'string', 'after' => 'nama_kepala_madrasah'],
                'foto_kepala_madrasah' => ['type' => 'string', 'after' => 'nip_kepala_madrasah'],
                'tanda_tangan_kepala_madrasah' => ['type' => 'string', 'after' => 'foto_kepala_madrasah'],
                'stempel_madrasah' => ['type' => 'string', 'after' => 'tanda_tangan_kepala_madrasah'],
                'kata_pengantar' => ['type' => 'longText', 'after' => 'stempel_madrasah'],
            ];

            foreach ($columns as $name => $spec) {
                if (!Schema::hasColumn('profile_madrasahs', $name)) {
                    $col = $table->{$spec['type']}($name)->nullable();
                    if (isset($spec['after']) && Schema::hasColumn('profile_madrasahs', $spec['after'])) {
                        $col->after($spec['after']);
                    }
                }
            }

            // Drop visi_misi if it exists and IF visi/misi are present (safety check)
            if (Schema::hasColumn('profile_madrasahs', 'visi_misi')) {
                // You might want to copy data first if needed, but for now we just structure structure.
                // $table->dropColumn('visi_misi'); 
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ... (Skipping full rollback logic for simplicity in this hotfix context)
    }
};
