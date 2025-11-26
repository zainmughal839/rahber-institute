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
        return $this->belongsTo(Session::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
