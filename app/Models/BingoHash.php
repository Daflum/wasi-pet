<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BingoHash extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_slug',
        'card_hash',
    ];
}
