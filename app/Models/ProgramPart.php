<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramPart extends Model
{
    protected $fillable = ['program_id', 'part_name'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'program_part_subjects');
    }
}

