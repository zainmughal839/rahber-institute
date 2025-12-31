<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McqAnswer extends Model
{
    protected $fillable = [
        'mcq_attempt_id','mcq_question_id','selected_option','is_correct'
    ];
}
