<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Industry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_submitter_id',
        'name',
        'address',
        'city',
        'contact_person',
        'email',
        'phone',
        'delivery_method_proposal',
        'is_synced',
        'status',
    ];

    protected $casts = [
        'is_synced' => 'boolean',
    ];

    /**
     * Get the student who proposed/submitted this industry.
     */
    public function studentSubmitter()
    {
        return $this->belongsTo(User::class, 'student_submitter_id');
    }

    /**
     * Get the quota allocations for this industry.
     */
    public function allocations()
    {
        return $this->hasMany(IndustryAllocation::class);
    }

    /**
     * Accessor: total quota from allocations in the active academic year.
     */
    public function getTotalQuotaAttribute(): int
    {
        return (int) $this->allocations()
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->sum('quota');
    }

    /**
     * Scope: only industries proposed by students.
     */
    public function scopeProposedByStudents($query)
    {
        return $query->whereNotNull('student_submitter_id');
    }

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
