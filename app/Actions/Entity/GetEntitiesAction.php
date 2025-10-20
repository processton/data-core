<?php

namespace App\Actions\Entity;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Collection;

class GetEntitiesAction
{
    /**
     * Execute the action to retrieve all entities.
     */
    public function execute(): Collection
    {
        return Entity::with(['fields', 'triggers'])->get();
    }
}
