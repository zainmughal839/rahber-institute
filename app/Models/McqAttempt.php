<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McqAttempt extends Model
{
   
    protected $fillable = [
        'mcq_paper_id',
        'student_id',
        'status',                 // ✅ ADD THIS
        'total_questions',
        'correct',
        'wrong',
        'score',
        'subjective_submitted',
        'subjective_obtained',
        'total_obtained',
        'mcq_started_at',
        'subjective_started_at',
    ];

    protected $casts = [
        'subjective_submitted' => 'boolean',
        'mcq_started_at' => 'datetime',
        'subjective_started_at' => 'datetime',
    ];

    public function answers()
    {
        return $this->hasMany(McqAnswer::class);
    }
    
}

