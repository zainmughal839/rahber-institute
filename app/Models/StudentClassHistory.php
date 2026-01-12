<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class StudentClassHistory extends Model
{
    protected $fillable = [
        'student_id',
        'old_class_subject_id',
        'new_class_subject_id',
        'description',
        'promoted_at',
    ];

    // Tell Laravel this is a date field
    protected $dates = [
        'promoted_at',
        'created_at',
        'updated_at',
    ];

    // OR (better for Laravel 9+) - use casts
    protected $casts = [
        'promoted_at' => 'datetime',
        // 'promoted_at' => 'datetime:Y-m-d H:i:s',  ← you can set custom format if you want
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function oldClassSubject()
    {
        return $this->belongsTo(ClassSubject::class, 'old_class_subject_id');
    }

    public function newClassSubject()
    {
        return $this->belongsTo(ClassSubject::class, 'new_class_subject_id');
    }

    // Optional: nice formatted attribute
    public function formattedPromotedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->promoted_at?->format('d M Y - h:i A') ?? 'N/A'
        );
    }
}