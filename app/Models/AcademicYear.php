<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the industry allocations for this academic year.
     */
    public function industryAllocations()
    {
        return $this->hasMany(IndustryAllocation::class);
    }

    /**
     * Get the supervisor allocations for this academic year.
     */
    public function supervisorAllocations()
    {
        return $this->hasMany(SupervisorAllocation::class);
    }

    /**
     * Get the internships for this academic year.
     */
    public function internships()
    {
        return $this->hasMany(Internship::class);
    }

    /**
     * Get the students for this academic year.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
