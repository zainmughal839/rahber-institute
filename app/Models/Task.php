<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'audience',
        'teacher_id',
        'task_cat_id',
        'session_program_id',
        'title',
        'task_start',
        'task_end',
        'paper_date',
        'teacher_heading',
        'teacher_desc',
        'student_heading',
        'student_desc',
        'is_completed',
    ];

    protected $casts = [
        'audience'     => 'array',
        'task_start'   => 'datetime',
        'task_end'     => 'datetime',
        'paper_date'   => 'datetime',
        'is_completed' => 'boolean',
    ];

    /* ✅ MULTIPLE STUDENT CATEGORIES */
    public function studentCategories()
    {
        return $this->belongsToMany(
            StuCategory::class,
            'task_stu_category'
        );
    }

    public function responses()
{
    return $this->hasMany(TaskResponse::class);
}



    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function category()
    {
        return $this->belongsTo(TaskCat::class, 'task_cat_id');
    }

    public function sessionProgram()
    {
        return $this->belongsTo(SessionProgram::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_task');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_task');
    }


}

