<?php

namespace App\Models;

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
     * Get the industry allocations for this department.
     */
    public function industryAllocations()
    {
        return $this->hasMany(IndustryAllocation::class);
    }
}
