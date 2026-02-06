<?php

namespace App\Enums;

enum AdministrationCategory: string
{
    case PERENCANAAN = 'perencanaan_pembelajaran';
    case PELAKSANAAN = 'pelaksanaan_evaluasi';
    case PENDUKUNG = 'administrasi_pendukung';

    public function label(): string
    {
        return match ($this) {
            self::PERENCANAAN => 'Perencanaan Pembelajaran',
            self::PELAKSANAAN => 'Pelaksanaan & Evaluasi',
            self::PENDUKUNG => 'Administrasi Pendukung',
        };
    }

    public function folderKey(): string
    {
        return match ($this) {
            self::PERENCANAAN => 'planning',
            self::PELAKSANAAN => 'execution',
            self::PENDUKUNG => 'support',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }
}
