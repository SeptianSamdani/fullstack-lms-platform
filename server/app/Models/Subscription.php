<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'plan_id', 'start_date', 'end_date', 'status'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }
    public function plan(): BelongsTo 
    { 
        return $this->belongsTo(SubscriptionPlan::class); 
    }
}