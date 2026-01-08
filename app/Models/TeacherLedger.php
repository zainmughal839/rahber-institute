<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherLedger extends Model
{
    use HasFactory;

    protected $table = 'teacher_ledger';

    protected $fillable = [
        'teacher_id',
        'amount',
        'type',
        'title',
        'description',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}