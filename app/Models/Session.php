<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions_p';

   protected $fillable = [
    'sessions_name',
    'start_date',
    'end_date',
    'description',
];

}