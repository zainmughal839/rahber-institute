<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'name',
        'cnic',
        'email',
        'phone',
        'address',
        'description',
        'picture',
        'cnic_front_image',
        'cnic_back_image',
        'academic_details',
        'salary',
    ];

    protected $casts = [
        'academic_details' => 'array',
    ];


    public function taskResponses()
{
    return $this->hasMany(TaskResponse::class);
}

}
