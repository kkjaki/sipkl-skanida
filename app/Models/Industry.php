<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Industry extends Model
{
    use HasFactory, SoftDeletes;
    
    protected static function booted()
    {
        static::deleting(function ($industry) {
            // Nullify unique identifier fields on soft delete to prevent constraint conflicts
            $industry->email = null;
            $industry->save();
        });
    }

    protected $fillable = [
        'student_submitter_id',
        'name',
        'address',
        'city',
        'contact_person',
        'email',
        'phone',
        'pic_name',
        'pic_position',
        'nip',
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
            ->whereHas('academicYear', fn ($q) => $q->where('is_active', true))
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

    /**
     * Get the currently active partnership (MoU).
     * If multiple MoU overlap, returns the most recent one.
     */
    public function getActivePartnershipAttribute(): ?IndustryPartnership
    {
        return $this->partnerships()
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->first();
    }

    /**
     * Check if industry has a valid (active) MoU.
     */
    public function getHasValidMouAttribute(): bool
    {
        return $this->active_partnership !== null;
    }

    /**
     * Get partnership status: 'active', 'warning', 'expired', or 'none'.
     */
    public function getPartnershipStatusAttribute(): string
    {
        $active = $this->active_partnership;

        if (! $active) {
            return 'none';
        }

        if ($active->is_expiring_soon) {
            return 'warning';
        }

        return 'active';
    }

    /**
     * Get days until active MoU expires (null if no active MoU).
     */
    public function getDaysUntilExpiredAttribute(): ?int
    {
        if (! $this->active_partnership) {
            return null;
        }

        return now()->diffInDays($this->active_partnership->end_date, false);
    }

    /**
     * Get initial internship date from active MoU (for plotting synchronization).
     * Returns null if no active MoU exists.
     *
     * CRITICAL: This is used by Plotting Controller to ensure students are only
     * assigned to industries with valid MoU.
     */
    public function getInitialInternshipDateAttribute(): ?string
    {
        return $this->active_partnership?->start_date?->format('Y-m-d');
    }
}
