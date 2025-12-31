<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectiveQuestion extends Model
{
    protected $fillable = ['mcq_paper_id','question','marks'];

    public function paper() {
        return $this->belongsTo(McqPaper::class);
    }
}