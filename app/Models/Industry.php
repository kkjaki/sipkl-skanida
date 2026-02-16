<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_submitter_id',
        'name',
        'address',
        'city',
        'contact_person',
        'phone',
        'delivery_method_proposal',
        'is_synced',
        'status',
        'quota',
    ];

    protected $casts = [
        'is_synced' => 'boolean',
    ];

    /**
     * Get the internships associated with the industry.
     */
    public function internships()
    {
        return $this->hasMany(Internship::class);
    }

    /**
     * Get the partnerships associated with the industry.
     */
    public function partnerships()
    {
        return $this->hasMany(IndustryPartnership::class);
    }
}
