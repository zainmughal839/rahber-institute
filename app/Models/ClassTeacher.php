<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Program;
use App\Models\SessionProgram;

use Illuminate\Database\Eloquent\Model;


class ClassTeacher extends Model
{
    protected $table = 'class_teacher';

    protected $fillable = [
        'class_subjects_id',
        'teacher_id',
        'status',
        'desc',
    ];

     public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class, 'class_subjects_id');
    }

    // ✅ CORRECT pivot table
    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'class_teacher_subject',   // ✅ correct table
            'class_teacher_id',
            'subject_id'
        );
    }

public function programs()
{
    return $this->belongsToMany(
        Program::class,
        'class_subject_program',
        'class_subject_id',
        'program_id'
    );
}

public function sessionProgram()
{
    return $this->belongsTo(SessionProgram::class, 'session_program_id');
}

}
