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

    // ✅ TEST FIELDS
    'has_test',
    'test_category_id',
    'test_type',
    'test_title',
    'test_desc',
    'test_orientation',
    'result_announce_at',
    'paper_submit_at',
    'total_marks',
    'passing_marks',
];

protected $casts = [
    'audience' => 'array',
    'task_start' => 'datetime',
    'task_end' => 'datetime',
    'paper_date' => 'datetime',
    'result_announce_at' => 'datetime',
    'paper_submit_at' => 'datetime',
    'has_test' => 'boolean',
];


public function subjects()
{
    return $this->belongsToMany(Subject::class, 'task_subject');
}

public function testCategory()
{
    return $this->belongsTo(TestCat::class, 'test_category_id');
}


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


    public function students() {
        return $this->belongsToMany(Student::class, 'student_task', 'task_id', 'student_id');
    }

    // In App\Models\Task.php – add this relationship
    public function classes()
    {
        return $this->belongsToMany(ClassSubject::class, 'task_class_subject', 'task_id', 'class_subject_id');
    }

}

