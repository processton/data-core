<?php

namespace App\Http\Controllers\Soap;

use App\Actions\Entity\CreateEntityAction;
use App\Actions\Entity\DeleteEntityAction;
use App\Actions\Entity\GetEntitiesAction;
use App\Actions\Entity\GetEntityAction;
use App\Actions\Entity\UpdateEntityAction;

class EntitySoapController
{
    /**
     * Get all entities.
     *
     * @return array
     */
    public function getEntities()
    {
        $action = new GetEntitiesAction;
        $entities = $action->execute();

        return [
            'success' => true,
            'message' => 'Entities retrieved successfully',
            'data' => $entities->toArray(),
        ];
    }

    /**
     * Get a single entity by ID.
     *
     * @param  int  $id
     * @return array
     */
    public function getEntity($id)
    {
        $action = new GetEntityAction;
        $entity = $action->execute((int) $id);

        if (! $entity) {
            return [
                'success' => false,
                'message' => 'Entity not found',
                'data' => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'Entity retrieved successfully',
            'data' => $entity->toArray(),
        ];
    }

    /**
     * Create a new entity.
     *
     * @param  string  $name
     * @param  string  $display_name
     * @param  string  $collection_name
     * @param  string|null  $description
     * @param  bool  $is_active
     * @return array
     */
    public function createEntity($name, $display_name, $collection_name, $description = null, $is_active = true)
    {
        try {
            $action = new CreateEntityAction;
            $entity = $action->execute([
                'name' => $name,
                'display_name' => $display_name,
                'collection_name' => $collection_name,
                'description' => $description,
                'is_active' => $is_active,
            ]);

            return [
                'success' => true,
                'message' => 'Entity created successfully',
                'data' => $entity->toArray(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create entity: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Update an existing entity.
     *
     * @param  int  $id
     * @param  string|null  $name
     * @param  string|null  $display_name
     * @param  string|null  $collection_name
     * @param  string|null  $description
     * @param  bool|null  $is_active
     * @return array
     */
    public function updateEntity($id, $name = null, $display_name = null, $collection_name = null, $description = null, $is_active = null)
    {
        $getAction = new GetEntityAction;
        $entity = $getAction->execute((int) $id);

        if (! $entity) {
            return [
                'success' => false,
                'message' => 'Entity not found',
                'data' => null,
            ];
        }

        try {
            $data = array_filter([
                'name' => $name,
                'display_name' => $display_name,
                'collection_name' => $collection_name,
                'description' => $description,
                'is_active' => $is_active,
            ], fn ($value) => $value !== null);

            $updateAction = new UpdateEntityAction;
            $entity = $updateAction->execute($entity, $data);

            return [
                'success' => true,
                'message' => 'Entity updated successfully',
                'data' => $entity->toArray(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update entity: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Delete an entity.
     *
     * @param  int  $id
     * @return array
     */
    public function deleteEntity($id)
    {
        $getAction = new GetEntityAction;
        $entity = $getAction->execute((int) $id);

        if (! $entity) {
            return [
                'success' => false,
                'message' => 'Entity not found',
                'data' => null,
            ];
        }

        try {
            $deleteAction = new DeleteEntityAction;
            $deleteAction->execute($entity);

            return [
                'success' => true,
                'message' => 'Entity deleted successfully',
                'data' => null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete entity: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }
}
