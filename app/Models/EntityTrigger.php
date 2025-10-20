<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntityTrigger extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'entity_id',
        'name',
        'event',
        'webhook_url',
        'http_method',
        'headers',
        'payload_template',
        'is_active',
        'retry_count',
        'timeout',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'headers' => 'array',
        'payload_template' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the entity that owns the trigger.
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }
}
