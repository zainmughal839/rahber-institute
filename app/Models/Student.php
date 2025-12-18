<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
     protected $fillable = [
        'name','father_name','cnic','phone','email','address','description',
        'stu_category_id','rollnum',
        'session_program_id','program_id','class_subject_id'
    ];

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


    public function tasks()
{
    return $this->belongsToMany(Task::class, 'student_task');
}



}
