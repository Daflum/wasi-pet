<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get the value attribute.
     * If the key ends with '_qr' or '_image', we assume it's a file path and return the storage URL.
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $key = $attributes['key'] ?? '';
                if ($value && (str_ends_with($key, '_qr') || str_ends_with($key, '_image'))) {
                    if (filter_var($value, FILTER_VALIDATE_URL)) {
                        return $value;
                    }
                    return asset('storage/' . $value);
                }
                return $value;
            }
        );
    }
}
