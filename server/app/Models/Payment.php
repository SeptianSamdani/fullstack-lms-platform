<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = ['user_id', 'payable_type', 'payable_id', 'amount', 'status', 'payment_method', 'confirmed_by', 'xendit_invoice_id'];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}