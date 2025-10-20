<?php

namespace App\Actions\Entity;

use App\Models\Entity;

class DeleteEntityAction
{
    /**
     * Execute the action to delete an entity.
     */
    public function execute(Entity $entity): bool
    {
        return $entity->delete();
    }
}
