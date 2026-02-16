<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'certificate_number',
        'issued_date',
        'file_path',
        'status',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    /**
     * Get the internship associated with the certificate.
     */
    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}
