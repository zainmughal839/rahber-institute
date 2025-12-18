<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskUserResponse extends Model
{
    protected $fillable = ['task_id', 'user_id', 'response_type', 'desc'];

    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
