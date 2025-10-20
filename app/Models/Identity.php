<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Identity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'account_id',
        'provider',
        'provider_id',
        'provider_data',
        'last_login_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'provider_data' => 'array',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the account that owns the identity.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
