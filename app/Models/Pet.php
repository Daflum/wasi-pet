<?php

namespace App\Models;

use Carbon\Carbon;
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
        'birth_date',
        'size',
        'description',
        'status',
        'image',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['age', 'age_label', 'species_label', 'species_icon', 'size_label'];

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
                'dog' => 'mdi-bone', // Huesito para perros
                'cat' => 'mdi-fish', // Pescadito para gatos
                default => 'mdi-paw',
            },
        );
    }

    /**
     * Get the size label attribute.
     */
    protected function sizeLabel(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => match ($attributes['size'] ?? null) {
                'small' => 'Pequeño',
                'medium' => 'Mediano',
                'large' => 'Grande',
                'extra_large' => 'Muy Grande',
                default => 'Tamaño: ' . ($attributes['size'] ?? 'Desconocido'),
            },
        );
    }

    /**
     * Get the pet's age in years, calculated from birth_date.
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => isset($attributes['birth_date'])
                ? Carbon::parse($attributes['birth_date'])->age
                : null,
        );
    }

    /**
     * Get the age label attribute.
     */
    protected function ageLabel(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if (!isset($attributes['birth_date'])) {
                    return 'Edad desconocida';
                }
                $age = Carbon::parse($attributes['birth_date'])->age;
                return $age . ' ' . ($age === 1 ? 'Año' : 'Años');
            }
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
