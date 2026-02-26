<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'teacher_id',
        'batch_id',
        'title',
        'file_path',
        'file_size',
        'file_type',
        'status',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Returns a human-readable file size string.
     */
    public function getReadableSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576)
            return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)
            return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
