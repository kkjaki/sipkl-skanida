<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * Daftar kelas XII yang valid untuk dropdown.
     */
    public const AVAILABLE_CLASSES = [
        'XII PPLG 1', 'XII PPLG 2', 'XII PPLG 3',
        'XII AKL 1', 'XII AKL 2', 'XII AKL 3',
        'XII MPLB 1', 'XII MPLB 2', 'XII MPLB 3',
        'XII PM 1', 'XII PM 2',
    ];

    // user_id is the primary key and foreign key to users
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'department_id',
        'academic_year_id',
        'nis',
        'place_of_birth',
        'date_of_birth',
        'class_name',
        'address',
        'phone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user that owns the student profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the department that the student belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the academic year that the student belongs to.
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Get the internship record for the student.
     */
    public function internship()
    {
        return $this->hasOne(Internship::class, 'student_id', 'user_id');
    }

    /**
     * Get all internship records for the student (across academic years).
     */
    public function internships()
    {
        return $this->hasMany(Internship::class, 'student_id', 'user_id');
    }
}
