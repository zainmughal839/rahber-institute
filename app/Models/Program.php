<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = [
        'name', 'shortname', 'program_code', 'description', 'divided_fees',
    ];

    
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'program_subject');
    }

    public function parts()
    {
        return $this->hasMany(ProgramPart::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'program_task');
    }

    public function classSubjects()
    {
        return $this->belongsToMany(ClassSubject::class, 'class_subject_program');
    }

    
    public function sessionPrograms()
    {
        return $this->belongsToMany(
            SessionProgram::class,
            'session_program_program', // pivot table
            'program_id',
            'session_program_id'
        );
    }
}