<?php

namespace App\Services\Entity;

use App\Models\Entity;
use App\Models\EntityIdentity;
use Illuminate\Support\Facades\DB;

/**
 * Service for managing entity identities.
 * 
 * This helper allows developers to register multiple identity keys for an entity.
 */
class EntityIdentityService
{
    /**
     * Register an identity for an entity.
     *
     * @param int $entityId The entity ID
     * @param string $identityKey The identity key (e.g., 'uuid', 'code', 'external_id')
     * @param string $identityValue The identity value
     * @param array|null $metadata Optional metadata
     * @return EntityIdentity
     */
    public function registerIdentity(int $entityId, string $identityKey, string $identityValue, ?array $metadata = null): EntityIdentity
    {
        return EntityIdentity::updateOrCreate(
            [
                'entity_id' => $entityId,
                'identity_key' => $identityKey,
                'identity_value' => $identityValue,
            ],
            [
                'metadata' => $metadata,
            ]
        );
    }

    /**
     * Register multiple identities for an entity.
     *
     * @param int $entityId The entity ID
     * @param array $identities Array of identities [['key' => 'uuid', 'value' => '...', 'metadata' => []], ...]
     * @return array
     */
    public function registerMultipleIdentities(int $entityId, array $identities): array
    {
        $results = [];

        DB::transaction(function () use ($entityId, $identities, &$results) {
            foreach ($identities as $identity) {
                $results[] = $this->registerIdentity(
                    $entityId,
                    $identity['key'],
                    $identity['value'],
                    $identity['metadata'] ?? null
                );
            }
        });

        return $results;
    }

    /**
     * Find an entity by its identity.
     *
     * @param string $identityKey The identity key
     * @param string $identityValue The identity value
     * @return Entity|null
     */
    public function findEntityByIdentity(string $identityKey, string $identityValue): ?Entity
    {
        $entityIdentity = EntityIdentity::where('identity_key', $identityKey)
            ->where('identity_value', $identityValue)
            ->first();

        return $entityIdentity?->entity;
    }

    /**
     * Get all identities for an entity.
     *
     * @param int $entityId The entity ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEntityIdentities(int $entityId)
    {
        return EntityIdentity::where('entity_id', $entityId)->get();
    }

    /**
     * Remove an identity from an entity.
     *
     * @param int $entityId The entity ID
     * @param string $identityKey The identity key
     * @param string $identityValue The identity value
     * @return bool
     */
    public function removeIdentity(int $entityId, string $identityKey, string $identityValue): bool
    {
        return EntityIdentity::where('entity_id', $entityId)
            ->where('identity_key', $identityKey)
            ->where('identity_value', $identityValue)
            ->delete() > 0;
    }
}
