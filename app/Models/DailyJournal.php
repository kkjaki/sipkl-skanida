<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'date',
        'activity',
        'status_attendance',
        'verification_status',
        'attachment_path',
        'rejection_note',
        'verified_at',
    ];

    protected $casts = [
        'date' => 'date',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the internship that the daily journal belongs to.
     */
    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}
