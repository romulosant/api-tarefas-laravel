<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status'];

    protected $table = 'tasks';

    protected $casts = [
        'status' => 'enum:pending,in_progress,done',
    ];

    
}
