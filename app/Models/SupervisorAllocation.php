<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'academic_year_id',
        'quota',
    ];

    /**
     * Get the supervisor associated with the allocation.
     */
    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'user_id');
    }

    /**
     * Get the academic year for the allocation.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
