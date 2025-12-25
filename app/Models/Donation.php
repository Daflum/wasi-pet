<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'message',
        'proof_path',
        'guest_contact',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
