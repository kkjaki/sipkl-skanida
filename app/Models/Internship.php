<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'industry_id',
        'supervisor_id',
        'academic_year_id',
        'start_date',
        'actual_end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'actual_end_date' => 'date',
    ];

    /**
     * Get the student associated with the internship.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'user_id');
    }

    /**
     * Get the industry associated with the internship.
     */
    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    /**
     * Get the teacher (supervisor) associated with the internship.
     */
    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'user_id');
    }

    /**
     * Get the academic year associated with the internship.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the daily journals for the internship.
     */
    public function dailyJournals()
    {
        return $this->hasMany(DailyJournal::class);
    }

    /**
     * Get the assessment scores for the internship.
     */
    public function assessmentScores()
    {
        return $this->hasMany(AssessmentScore::class);
    }

    /**
     * Get the certificates for the internship.
     */
    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}
