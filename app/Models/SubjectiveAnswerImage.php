<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SubjectiveAnswerImage extends Model
{
    protected $fillable = ['subjective_answer_id','image_path'];

    public function answer()
    {
        return $this->belongsTo(SubjectiveAnswer::class);
    }
}