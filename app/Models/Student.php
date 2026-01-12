<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
     protected $fillable = [
        'name','father_name','cnic','phone','email','address','description','rollnum',
        'session_program_id','program_id','class_subject_id','student_image'
    ];

    // MULTIPLE CATEGORIES
    public function categories()
    {
        return $this->belongsToMany(
            StuCategory::class,
            'student_stu_category',
            'student_id',
            'stu_category_id'
        )->withTimestamps();
    }

    public function classHistories()
    {
        return $this->hasMany(StudentClassHistory::class)->orderBy('promoted_at', 'desc');
    }

    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }

    public function category()
    {
        return $this->belongsTo(StuCategory::class, 'stu_category_id');
    }

    public function sessionProgram()
    {
        return $this->belongsTo(SessionProgram::class, 'session_program_id');
    }

    public function program()
    {
        return $this->belongsTo(\App\Models\Program::class, 'program_id');
    }

    public function ledgers()
    {
        return $this->hasMany(AllLedger::class, 'student_id', 'id');
    }

   public function challans()
    {
        return $this->belongsToMany(Challan::class, 'challan_students')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'student_task');
    }



}
