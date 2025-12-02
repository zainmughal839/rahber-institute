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
        'email',
        'address',
        'description',
        'stu_category_id',
        'rollnum',
        'session_program_id',
    ];

    public function category()
    {
        return $this->belongsTo(StuCategory::class, 'stu_category_id');
    }

    public function sessionProgram()
    {
        return $this->belongsTo(SessionProgram::class, 'session_program_id');
    }

    public function ledgers()
    {
        return $this->hasMany(AllLedger::class, 'student_id', 'id');
    }

    public function totalCredits()
    {
        return $this->ledgers()->where('type', 'credit')->sum('amount');
    }

    public function totalDebits()
    {
        return $this->ledgers()->where('type', 'debit')->sum('amount');
    }
}