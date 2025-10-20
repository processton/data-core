<?php

namespace App\Http\Controllers\Soap;

use App\Models\Entity;

class EntitySoapController
{
    /**
     * Get all entities.
     *
     * @return array
     */
    public function getEntities()
    {
        $entities = Entity::with(['fields', 'triggers'])->get();

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
        $entity = Entity::with(['fields', 'triggers'])->find($id);

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
            $entity = Entity::create([
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
        $entity = Entity::find($id);

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

            $entity->update($data);

            return [
                'success' => true,
                'message' => 'Entity updated successfully',
                'data' => $entity->fresh()->toArray(),
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
        $entity = Entity::find($id);

        if (! $entity) {
            return [
                'success' => false,
                'message' => 'Entity not found',
                'data' => null,
            ];
        }

        try {
            $entity->delete();

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
