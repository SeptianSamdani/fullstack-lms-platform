<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = ['user_id', 'payable_type', 'payable_id', 'amount', 'status', 'payment_method'];
    public function payable(): MorphTo 
    { 
        return $this->morphTo(); 
    }
}