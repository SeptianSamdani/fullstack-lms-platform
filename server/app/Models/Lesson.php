<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use SoftDeletes; 
    
    protected $fillable = ['module_id', 'title', 'content_url', 'content', 'content_type', 'duration', 'order'];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
