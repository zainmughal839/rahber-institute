<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionProgram extends Model
{
     protected $table = 'session_program';

    protected $fillable = ['session_id'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }



    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }


    public function programs()
{
    return $this->belongsToMany(Program::class, 'session_program_program', 'session_program_id', 'program_id');
}

    public function students()
    {
        return $this->hasMany(Student::class, 'session_program_id');
    }


public function sessionPrograms()
{
    return $this->belongsToMany(
        SessionProgram::class,
        'session_program_program',
        'program_id',
        'session_program_id'
    );
}




}