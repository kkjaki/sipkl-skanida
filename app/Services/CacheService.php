<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Centralized cache management service.
 *
 * All cache keys and flush logic are maintained here so that
 * cache invalidation is consistent across the entire application.
 * When data changes in any controller, call the appropriate flush
 * method instead of scattering Cache::forget() calls everywhere.
 *
 * Cache Key Registry:
 *  - dashboard:admin                  → Admin dashboard statistics
 *  - dashboard:curriculum             → Curriculum dashboard statistics
 *  - dashboard:supervisor:{userId}    → Per-supervisor dashboard statistics
 *  - supervisor_allocations:{yearId}  → Supervisor allocation listing
 *  - grade_recap:{yearId}             → Grade recap data
 */
class CacheService
{
    // ──────────────────────────────────────────────
    //  Cache Key Constants
    // ──────────────────────────────────────────────

    public const KEY_DASHBOARD_ADMIN = 'dashboard:admin';

    public const KEY_DASHBOARD_CURRICULUM = 'dashboard:curriculum';

    public const PREFIX_DASHBOARD_SUPERVISOR = 'dashboard:supervisor:';

    public const PREFIX_SUPERVISOR_ALLOCATIONS = 'supervisor_allocations:';

    public const PREFIX_GRADE_RECAP = 'grade_recap:';

    // ──────────────────────────────────────────────
    //  Flush Methods
    // ──────────────────────────────────────────────

    /**
     * Flush ALL dashboard caches (admin + curriculum + every supervisor).
     *
     * Call this whenever data changes that could affect any dashboard
     * statistics: students, industries, internships, supervisors, etc.
     */
    public static function flushDashboard(): void
    {
        Cache::forget(self::KEY_DASHBOARD_ADMIN);
        Cache::forget(self::KEY_DASHBOARD_CURRICULUM);

        $supervisorIds = User::role('supervisor')->pluck('id');
        foreach ($supervisorIds as $id) {
            Cache::forget(self::PREFIX_DASHBOARD_SUPERVISOR.$id);
        }
    }

    /**
     * Flush the grade recap cache for the active academic year.
     *
     * Call this when assessment scores change or internship
     * statuses are updated.
     */
    public static function flushGradeRecap(): void
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $key = self::PREFIX_GRADE_RECAP.($activeYear?->id ?? 'all');
        Cache::forget($key);
    }

    /**
     * Flush supervisor allocation cache for the active academic year.
     *
     * Call this when supervisor allocations or quotas are modified.
     */
    public static function flushSupervisorAllocations(): void
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            Cache::forget(self::PREFIX_SUPERVISOR_ALLOCATIONS.$activeYear->id);
        }
    }

    /**
     * Nuclear option: flush every known cache key.
     *
     * Call this when something fundamental changes (e.g. active
     * academic year switch) that invalidates virtually everything.
     */
    public static function flushAll(): void
    {
        self::flushDashboard();
        self::flushGradeRecap();
        self::flushSupervisorAllocations();
    }
}
