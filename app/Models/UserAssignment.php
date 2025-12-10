<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assignable_id',
        'assignable_type',
        'panel_type',
        'email',
        'password_set',
        'plain_password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignable()
    {
        return $this->morphTo();
    }
}