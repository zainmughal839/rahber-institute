<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCat extends Model
{
    protected $table = 'task_cat';

    protected $fillable = [
        'name', 'desc'
    ];
}
