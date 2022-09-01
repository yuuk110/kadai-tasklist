<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
     protected $fillable = ['user_id'];

    public function tasks()
    {
        return $this->belongsTo(Task::class);
    }
}
