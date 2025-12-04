<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionProgram extends Model
{
    protected $table = 'session_program';

    protected $fillable = [
        'session_id',
        'program_id',
        'seats',
        'fees',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'session_program_id');
    }
}