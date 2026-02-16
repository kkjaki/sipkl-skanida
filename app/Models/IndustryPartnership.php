<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryPartnership extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry_id',
        'start_date',
        'end_date',
        'mou_file_path',
        'agreement_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the industry associated with the partnership.
     */
    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
