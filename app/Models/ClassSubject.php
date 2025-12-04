<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    protected $fillable = [
        'class_name',
        'session_program_id',
        'status',
        'desc',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject_subject');
    }

    public function sessionProgram()
    {
        return $this->belongsTo(SessionProgram::class);
    }
}