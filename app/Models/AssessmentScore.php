<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'indicator_id',
        'score_industry',
        'score_school',
    ];

    /**
     * Get the internship associated with the score.
     */
    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    /**
     * Get the evaluation indicator for the score.
     */
    public function indicator()
    {
        return $this->belongsTo(EvaluationIndicator::class, 'indicator_id');
    }
}
