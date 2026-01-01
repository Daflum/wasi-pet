<?php

namespace App\Models;

use App\Observers\AdoptionRequestObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([AdoptionRequestObserver::class])]
class AdoptionRequest extends Model
{
    use Notifiable;

    protected $fillable = [
        'pet_id',
        'name',
        'email',
        'dni',
        'phone',
        'address',
        'status',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
