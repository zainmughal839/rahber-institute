<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'father_name',
        'cnic',
        'phone',
        'fees',
        'rollnum',
        'session_program_id',
    ];

    public function sessionProgram()
    {
        return $this->belongsTo(SessionProgram::class, 'session_program_id');
    }

    public function session()
    {
        return $this->sessionProgram->session;
    }

    public function program()
    {
        return $this->sessionProgram->program;
    }
}
