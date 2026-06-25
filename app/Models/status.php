<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const PENDING = 1;
    const IN_PROGRESS = 2;
    const DONE = 3;

    protected $fillable = ["nome"];


    public function tasks()
{
    return $this->hasMany(task::class);
}
}
