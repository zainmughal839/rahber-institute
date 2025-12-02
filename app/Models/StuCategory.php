<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StuCategory extends Model
{
    protected $table = 'stu_category';

    protected $fillable = [
        'name',
        'desc',
    ];
}