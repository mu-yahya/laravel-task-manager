<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'deadline', 'status', 'user_id']; // 

    protected $casts = [
        'deadline' => 'date', // 'datetime' ,
        'status' => 'integer',
        'user_id' => 'integer',
        'title' => 'string',
        'description' => 'string'
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
