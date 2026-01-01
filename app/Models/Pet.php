<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'gender',
        'age',
        'size',
        'description',
        'status',
        'image',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pet) {
            $pet->slug = Str::slug($pet->name);
        });
    }

    /**
     * Scope a query to only include available pets.
     */
    public function scopeAvailable(Builder $query): void
    {
        $query->where('status', '!=', 'Adoptado');
    }

    /**
     * Get the species label attribute.
     */
    protected function speciesLabel(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => match ($attributes['type']) {
                'dog' => 'Perro',
                'cat' => 'Gato',
                default => 'Otro',
            },
        );
    }

    /**
     * Get the species icon attribute.
     */
    protected function speciesIcon(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => match ($attributes['type']) {
                'dog' => 'mdi-dog',
                'cat' => 'mdi-cat',
                default => 'mdi-paw',
            },
        );
    }

    /**
     * Get the age label attribute.
     */
    protected function ageLabel(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['age'] . ' Años',
        );
    }

    /**
     * Get the pet's image.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value)) {
                    return 'https://via.placeholder.com/600x400';
                }
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    return $value;
                }
                return asset('storage/' . $value);
            }
        );
    }

    public function adoptionRequests(): HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
