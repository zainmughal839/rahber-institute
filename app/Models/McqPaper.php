<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McqPaper extends Model
{
    protected $fillable = [
    'teacher_id',
    'task_id',
    'title',
    'description',
    'status',
    'per_mcqs_num',
    'per_mcqs_time',
    'subjective_time',        // ← NEW
    'marks_per_mcq',
    'result_mode',
    'result_date',
    'total_subjective_marks',
    'cover_image',
];

    protected $casts = [
        'result_date' => 'datetime',  
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'mcq_paper_student');
    }

    public function questions()
    {
        return $this->belongsToMany(McqQuestion::class, 'mcq_paper_questions');
    }

    // ← REMOVE this if you don't need single bank anymore


    // ← ADD THIS REQUIRED RELATIONSHIP
    public function banks()
    {
        return $this->belongsToMany(McqBank::class, 'mcq_paper_bank');
    }

    //   public function getTotalMarksAttribute()
    // {
    //     return $this->per_mcqs_num;
    // }




    public function subjectiveQuestions()
    {
        return $this->hasMany(SubjectiveQuestion::class);
    }

    public function getTotalMarksAttribute()
    {
        return $this->per_mcqs_num * $this->marks_per_mcq;
    }
}