<?php

namespace App\Models;

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

    // Relations
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class, 'class_subjects_id');
    }
}