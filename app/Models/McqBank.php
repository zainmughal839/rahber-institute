<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McqBank extends Model
{
    protected $fillable = [
        'teacher_id',
        'mcq_category_id',
        'name',
        'description',
        'status'
    ];

  public function category()
    {
        return $this->belongsTo(McqCategory::class, 'mcq_category_id');
    }


    public function questions()
    {
        return $this->hasMany(McqQuestion::class);
    }
    
}



