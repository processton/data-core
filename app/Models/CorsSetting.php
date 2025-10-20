<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorsSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'allowed_origins',
        'allowed_methods',
        'allowed_headers',
        'exposed_headers',
        'max_age',
        'supports_credentials',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'supports_credentials' => 'boolean',
    ];
}
