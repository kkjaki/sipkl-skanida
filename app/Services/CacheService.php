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

    public const PREFIX_PLACEMENTS = 'placements:';

    public const PREFIX_JOURNAL_VALIDATIONS = 'journal_validations:';

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
     * Flush placement cache for a specific department in the active year.
     *
     * Call this when placements are created or deleted.
     */
    public static function flushPlacements(int $departmentId): void
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        if ($activeYear) {
            Cache::forget(self::PREFIX_PLACEMENTS."dept:{$departmentId}:year:{$activeYear->id}");
        }
    }

    /**
     * Flush journal validation cache for a specific internship.
     *
     * Call this after bulk verify/reject actions to ensure
     * the cached paginated results are refreshed.
     */
    public static function flushJournalValidations(int $internshipId): void
    {
        // Flush all pages/filter combinations for this internship
        // by using a tagged approach with pattern-based forget.
        // Since Laravel's file/database drivers don't support tags,
        // we flush by known filter combinations.
        $statuses = ['all', 'pending', 'verified', 'rejected'];
        $sorts = ['date_desc', 'date_asc'];

        foreach ($statuses as $status) {
            foreach ($sorts as $sort) {
                // Flush first 10 pages (covers typical usage)
                for ($page = 1; $page <= 10; $page++) {
                    Cache::forget(
                        self::PREFIX_JOURNAL_VALIDATIONS."{$internshipId}:{$status}:{$sort}:page:{$page}"
                    );
                }
            }
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

        // Flush all placement caches (iterate departments)
        $departmentIds = \App\Models\Department::pluck('id');
        foreach ($departmentIds as $deptId) {
            self::flushPlacements($deptId);
        }
    }
}
