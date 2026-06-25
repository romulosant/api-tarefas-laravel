<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status_id'
    ];

    // UMA task pertence a UM status
    public function status()
    {
        return $this->belongsTo(status::class);
    }
}