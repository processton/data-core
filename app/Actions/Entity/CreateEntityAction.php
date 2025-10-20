<?php

namespace App\Actions\Entity;

use App\Models\Entity;

class CreateEntityAction
{
    /**
     * Execute the action to create a new entity.
     */
    public function execute(array $data): Entity
    {
        return Entity::create($data);
    }
}
