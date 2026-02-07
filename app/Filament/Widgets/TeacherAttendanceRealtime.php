<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class TeacherAttendanceRealtime extends Widget
{
    protected static string $view = 'filament.widgets.teacher-attendance-realtime';

    // Sort order so it appears after Welcome widget
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Guru'); // Verify role
    }

    public function getViewData(): array
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', now())
            ->first();

        return [
            'attendance' => $attendance,
        ];
    }
}
