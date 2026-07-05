<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

class Status extends Model

{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];



    const PENDING = 1;
    const IN_PROGRESS = 2;
    const DONE = 3;

    protected $fillable = ["nome"];


    public function tasks()
    {
        return $this->hasMany(task::class);
    }
}
