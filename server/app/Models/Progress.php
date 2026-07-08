<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progress extends Model
{
    protected $fillable = ['user_id', 'lesson_id', 'completed_at'];
    protected $casts = ['completed_at' => 'datetime'];

    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }
    public function lesson(): BelongsTo 
    { 
        return $this->belongsTo(Lesson::class); 
    }
}