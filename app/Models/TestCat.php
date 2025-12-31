<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCat extends Model
{
    use HasFactory;

    protected $table = 'test_cat';

    protected $fillable = ['name', 'desc'];
}
