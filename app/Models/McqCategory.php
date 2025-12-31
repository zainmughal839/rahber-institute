<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class McqCategory extends Model
{
    protected $fillable = ['name','description','status'];

    public function banks()
    {
        return $this->hasMany(McqBank::class);
    }
}






