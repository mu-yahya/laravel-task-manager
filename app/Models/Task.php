<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'deadline', 'status']; // 

    protected $casts = [
        'birthdate' => 'date', // 'datetime' ,
        'status' => 'integer',
        'title' => 'string',
        'description' => 'string'
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
