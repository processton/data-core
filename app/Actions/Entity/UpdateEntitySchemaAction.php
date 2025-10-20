<?php

namespace App\Actions\Entity;

use App\Models\Entity;

class UpdateEntitySchemaAction
{
    /**
     * Execute the action to update an entity's schema.
     */
    public function execute(Entity $entity, array $schema): Entity
    {
        $entity->update(['schema' => $schema]);

        return $entity->fresh();
    }
}
