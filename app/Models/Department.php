<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Normalize: kode program keahlian selalu UPPERCASE.
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtoupper(trim($value)),
        );
    }

    /**
     * Peta warna badge per kode departemen (Single Source of Truth).
     * Digunakan oleh <x-department-badge> dan Alpine.js views.
     */
    public const BADGE_COLORS = [
        'PPLG' => 'bg-blue-500/10 text-blue-600 border-blue-500/20 dark:bg-blue-500/20 dark:text-blue-400',
        'AKL' => 'bg-amber-500/10 text-amber-600 border-amber-500/20 dark:bg-amber-500/20 dark:text-amber-400',
        'MPLB' => 'bg-purple-500/10 text-purple-600 border-purple-500/20 dark:bg-purple-500/20 dark:text-purple-400',
        'PM' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-400',
    ];

    public const BADGE_DEFAULT = 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-white/[0.06] dark:text-gray-300 dark:border-amoled-border';

    /**
     * Accessor: badge CSS classes for this department.
     */
    public function getBadgeColorAttribute(): string
    {
        return self::BADGE_COLORS[strtoupper($this->code)] ?? self::BADGE_DEFAULT;
    }

    /**
     * Get the industry allocations for this department.
     */
    public function industryAllocations()
    {
        return $this->hasMany(IndustryAllocation::class);
    }

    /**
     * Get the students for this department.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the supervisors for this department.
     */
    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }
}
