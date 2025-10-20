<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'collection_name',
        'schema',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'schema' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the fields for the entity.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(EntityField::class);
    }

    /**
     * Get the triggers for the entity.
     */
    public function triggers(): HasMany
    {
        return $this->hasMany(EntityTrigger::class);
    }

    /**
     * Get the identities for the entity.
     */
    public function identities(): HasMany
    {
        return $this->hasMany(EntityIdentity::class);
    }
}
