<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'audience',
        'session_program_id',
        'title',
        'teacher_desc',
        'start_at',
        'end_at',
        'is_active'
    ];

    protected $casts = [
        'audience'   => 'array',
        'start_at'   => 'datetime',
        'end_at'     => 'datetime',
        'is_active'  => 'boolean',
    ];

    /* ================= RELATIONS ================= */

    // MULTIPLE teachers
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'announcement_teacher');
    }

    public function sessionProgram()
    {
        return $this->belongsTo(SessionProgram::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'announcement_program');
    }

    public function studentCategories()
    {
        return $this->belongsToMany(StuCategory::class, 'announcement_stu_category');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'announcement_student');
    }
}
