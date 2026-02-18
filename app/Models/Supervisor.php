<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    // user_id is the primary key and foreign key to users
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'department_id',
        'nip',
    ];

    /**
     * Get the user that owns the supervisor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the department that the supervisor belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the internships managed by the supervisor.
     */
    public function internships()
    {
        return $this->hasMany(Internship::class, 'supervisor_id', 'user_id');
    }
    /**
     * Get the quota allocations for the supervisor.
     */
    public function allocations()
    {
        return $this->hasMany(SupervisorAllocation::class, 'supervisor_id', 'user_id');
    }
}
