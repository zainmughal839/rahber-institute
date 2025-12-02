<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllLedger extends Model
{
    protected $table = 'all_ledger';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'amount',
        'type',
        'ledger_category',
        'description',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
