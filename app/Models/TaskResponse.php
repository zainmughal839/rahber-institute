<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'teacher_id',
        'response_type',
        'desc',
        'd_married_points',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
