<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McqQuestion extends Model
{
    protected $fillable = [
        'mcq_bank_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'reason'
    ];

    public function bank()
    {
        return $this->belongsTo(McqBank::class);
    }
}




