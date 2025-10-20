<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceFeature extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_enabled',
        'module_name',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'metadata' => 'array',
    ];
}
