<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncErrorLog extends Model
{
    protected $fillable = [
        'sync_type',
        'batch_id',
        'rdm_id',
        'nama',
        'nis_nip',
        'kelas',
        'error_type',
        'error_column',
        'error_message',
        'is_resolved',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];

    /**
     * Scope for unresolved errors
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope for specific batch
     */
    public function scopeForBatch($query, string $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    /**
     * Parse error message to extract column name
     */
    public static function parseErrorColumn(string $message): ?string
    {
        // Match patterns like "Column 'nama_ayah' cannot be null"
        if (preg_match("/Column '([^']+)'/", $message, $matches)) {
            return $matches[1];
        }
        // Match patterns like "Duplicate entry ... for key 'students_nisn_unique'"
        if (preg_match("/for key '([^']+)'/", $message, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Parse error type from message
     */
    public static function parseErrorType(string $message): string
    {
        if (str_contains($message, 'cannot be null')) {
            return 'null_column';
        }
        if (str_contains($message, 'Duplicate entry')) {
            return 'duplicate';
        }
        if (str_contains($message, 'Incorrect')) {
            return 'invalid_format';
        }
        return 'unknown';
    }
}
