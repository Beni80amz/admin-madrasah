<?php

namespace App\Filament\Resources\LeaveRequestResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Nama Pemohon')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->required(),
                Select::make('type')
                    ->label('Jenis Izin')
                    ->options([
                        'sakit' => 'Sakit',
                        'izin' => 'Izin',
                    ])
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Mulai Tanggal')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Sampai Tanggal')
                    ->required(),
                Textarea::make('reason')
                    ->label('Alasan')
                    ->columnSpanFull()
                    ->required(),
                \Filament\Forms\Components\FileUpload::make('attachment')
                    ->label('Lampiran')
                    ->disk('public') // Assuming local public disk
                    ->directory('leave_requests')
                    ->image()
                    ->openable()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->disabled() // Status changed via actions usually
                    ->required(),
                Textarea::make('rejection_note')
                    ->label('Catatan Penolakan (Jika ditolak)')
                    ->columnSpanFull()
                    ->visible(fn($get) => $get('status') === 'rejected'),
            ]);
    }
}
