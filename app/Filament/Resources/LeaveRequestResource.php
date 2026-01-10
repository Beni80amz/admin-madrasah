<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages\CreateLeaveRequest;
use App\Filament\Resources\LeaveRequestResource\Pages\EditLeaveRequest;
use App\Filament\Resources\LeaveRequestResource\Pages\ListLeaveRequests;
use App\Filament\Resources\LeaveRequestResource\Schemas\LeaveRequestForm;
use App\Filament\Resources\LeaveRequestResource\Tables\LeaveRequestsTable;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Permohonan Izin';
    protected static ?string $pluralModelLabel = 'Permohonan Izin';
    protected static ?string $modelLabel = 'Permohonan Izin';

    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 99;
    protected static ?string $slug = 'permohonan-izin-belajar';

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function form(Schema $schema): Schema
    {
        return LeaveRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
            'edit' => EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
