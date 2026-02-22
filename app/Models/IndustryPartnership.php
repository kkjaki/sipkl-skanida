<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryPartnership extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry_id',
        'document_number',
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

    /**
     * Check if partnership is currently active.
     */
    public function getIsActiveAttribute(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }

    /**
     * Check if partnership is expiring soon (within 30 days).
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        $daysUntilExpiry = now()->diffInDays($this->end_date, false);

        return $daysUntilExpiry > 0 && $daysUntilExpiry <= 30;
    }

    /**
     * Get days until expiry (negative if expired).
     */
    public function getDaysUntilExpiryAttribute(): int
    {
        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Get status badge HTML for display.
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_active) {
            if ($this->is_expiring_soon) {
                // Warning: Expiring soon
                return '<span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-yellow-500/10 text-yellow-600 border-yellow-500/20 dark:bg-yellow-500/20 dark:text-yellow-400 dark:border-yellow-500/30">'
                    .$this->days_until_expiry.' hari lagi</span>';
            }

            // Active
            return '<span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30">Aktif</span>';
        }

        // Expired
        return '<span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-500/10 text-red-500 border-red-500/20 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30">Expired</span>';
    }

    /**
     * Scope: Filter only active partnerships.
     */
    public function scopeActive($query)
    {
        return $query->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    /**
     * Scope: Filter partnerships expiring soon (within 30 days).
     */
    public function scopeExpiringSoon($query)
    {
        return $query->whereDate('end_date', '>=', now())
            ->whereDate('end_date', '<=', now()->addDays(30));
    }
}
