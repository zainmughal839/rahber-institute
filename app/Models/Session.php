<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'sessions_p';

    // Fillable fields
    protected $fillable = [
        'start_date',
        'end_date',
        'description',
    ];
}
