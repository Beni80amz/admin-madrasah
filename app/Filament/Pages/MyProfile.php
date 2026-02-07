<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Teachers\Schemas\TeacherForm;
use App\Models\Teacher;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class MyProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Profil Saya';

    protected static ?string $title = 'Profil Saya';

    protected static ?string $slug = 'my-profile';

    protected static ?string $navigationGroup = 'Setting';

    protected static ?int $navigationSort = -1;

    protected string $view = 'filament.pages.my-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if ($teacher) {
            $this->form->fill($teacher->toArray());
        } else {
            // If superadmin or user without teacher record, fill with user data
            $this->form->fill([
                'nama_lengkap' => $user->name,
                'nip' => $user->email, // Using email as filler if NIP missing
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return TeacherForm::configure($schema)
            ->model($this->teacher)
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $user = Auth::user();
            $teacher = $user->teacher;

            if ($teacher) {
                $teacher->update($data);

                // Also sync user name if nama_lengkap changed
                if (isset($data['nama_lengkap']) && $user->name !== $data['nama_lengkap']) {
                    $user->update(['name' => $data['nama_lengkap']]);
                }
            } else {
                // If user doesn't have a teacher record, we don't handle creation here 
                // as it usually requires specific IDs (jabatan, etc.)
                // But we can update the user name
                if (isset($data['nama_lengkap'])) {
                    $user->update(['name' => $data['nama_lengkap']]);
                }
            }

            Notification::make()
                ->title('Profil Berhasil Diperbarui')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Memperbarui Profil')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        // Allow access to teachers and admins
        return $user && ($user->hasRole(['teacher', 'Teacher', 'Guru', 'super_admin', 'Superadmin', 'admin', 'Admin', 'kepala_sekolah', 'Kepala Sekolah']));
    }
}
