<?php

namespace App\Observers;

use App\Models\AcademicCalendar;
use App\Models\Holiday;

class AcademicCalendarObserver
{
    /**
     * Handle the AcademicCalendar "created" event.
     */
    public function created(AcademicCalendar $academicCalendar): void
    {
        if ($academicCalendar->kategori === 'Hari Libur') {
            Holiday::create([
                'academic_calendar_id' => $academicCalendar->id,
                'title' => $academicCalendar->nama_kegiatan,
                'start_date' => $academicCalendar->tanggal_mulai,
                'end_date' => $academicCalendar->tanggal_selesai,
                'description' => $academicCalendar->keterangan,
            ]);
        }
    }

    /**
     * Handle the AcademicCalendar "updated" event.
     */
    public function updated(AcademicCalendar $academicCalendar): void
    {
        if ($academicCalendar->kategori === 'Hari Libur') {
            Holiday::updateOrCreate(
                ['academic_calendar_id' => $academicCalendar->id],
                [
                    'title' => $academicCalendar->nama_kegiatan,
                    'start_date' => $academicCalendar->tanggal_mulai,
                    'end_date' => $academicCalendar->tanggal_selesai,
                    'description' => $academicCalendar->keterangan,
                ]
            );
        } else {
            // If category changed from 'Hari Libur' to something else, delete the holiday
            Holiday::where('academic_calendar_id', $academicCalendar->id)->delete();
        }
    }

    /**
     * Handle the AcademicCalendar "deleted" event.
     */
    public function deleted(AcademicCalendar $academicCalendar): void
    {
        Holiday::where('academic_calendar_id', $academicCalendar->id)->delete();
    }
}
