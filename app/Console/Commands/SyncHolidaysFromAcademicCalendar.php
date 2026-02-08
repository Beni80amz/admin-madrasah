<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncHolidaysFromAcademicCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-holidays-from-academic-calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync holidays from academic calendar entries with kategori "Hari Libur"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sync from Academic Calendar...');

        $academicEntries = \App\Models\AcademicCalendar::where('kategori', 'Hari Libur')->get();

        $count = 0;
        foreach ($academicEntries as $entry) {
            \App\Models\Holiday::updateOrCreate(
                ['academic_calendar_id' => $entry->id],
                [
                    'title' => $entry->nama_kegiatan,
                    'start_date' => $entry->tanggal_mulai,
                    'end_date' => $entry->tanggal_selesai,
                    'description' => $entry->keterangan,
                ]
            );
            $count++;
        }

        $this->info("Successfully synced {$count} holiday entries.");
    }
}
