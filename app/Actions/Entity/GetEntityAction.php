<?php

namespace App\Actions\Entity;

use App\Models\Entity;

class GetEntityAction
{
    /**
     * Execute the action to retrieve a single entity.
     */
    public function execute(int $id): ?Entity
    {
        return Entity::with(['fields', 'triggers'])->find($id);
    }
}
