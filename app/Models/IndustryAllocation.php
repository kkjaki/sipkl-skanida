<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry_id',
        'department_id',
        'academic_year_id',
        'quota',
    ];

    protected $casts = [
        'quota' => 'integer',
    ];

    /**
     * Get the industry that owns the allocation.
     */
    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    /**
     * Get the department for the allocation.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the academic year for the allocation.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
