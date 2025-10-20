<?php

namespace App\Actions\Entity;

use App\Models\Entity;

class UpdateEntityAction
{
    /**
     * Execute the action to update an existing entity.
     */
    public function execute(Entity $entity, array $data): Entity
    {
        $entity->update($data);

        return $entity->fresh();
    }
}
