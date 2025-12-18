<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'book_name',
        'book_short_name',
    ];


  public function programs()
{
    return $this->belongsToMany(Program::class, 'program_subject');
}


}