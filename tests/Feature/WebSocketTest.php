<?php

namespace Tests\Feature;

use App\Events\AccountEvent;
use App\Events\CorsSettingEvent;
use App\Events\EntityEvent;
use App\Events\EntityFieldEvent;
use App\Events\EntityTriggerEvent;
use App\Events\IdentityEvent;
use App\Events\PermissionEvent;
use App\Events\RoleEvent;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class WebSocketTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_account_list_broadcasts_event(): void
    {
        $response = $this->getJson('/api/v1/accounts');

        $response->assertStatus(200);
        Event::assertDispatched(AccountEvent::class);
    }

    public function test_account_create_broadcasts_event(): void
    {
        $response = $this->postJson('/api/v1/accounts');

        $response->assertStatus(200);
        Event::assertDispatched(AccountEvent::class);
    }

    public function test_account_get_broadcasts_event(): void
    {
        $response = $this->getJson('/api/v1/accounts/1');

        $response->assertStatus(200);
        Event::assertDispatched(AccountEvent::class);
    }

    public function test_account_update_broadcasts_event(): void
    {
        $response = $this->putJson('/api/v1/accounts/1');

        $response->assertStatus(200);
        Event::assertDispatched(AccountEvent::class);
    }

    public function test_account_delete_broadcasts_event(): void
    {
        $response = $this->deleteJson('/api/v1/accounts/1');

        $response->assertStatus(200);
        Event::assertDispatched(AccountEvent::class);
    }

    public function test_entity_operations_broadcast_events(): void
    {
        $this->getJson('/api/v1/entities');
        Event::assertDispatched(EntityEvent::class);

        Event::fake();
        $this->postJson('/api/v1/entities');
        Event::assertDispatched(EntityEvent::class);

        Event::fake();
        $this->getJson('/api/v1/entities/1');
        Event::assertDispatched(EntityEvent::class);

        Event::fake();
        $this->putJson('/api/v1/entities/1');
        Event::assertDispatched(EntityEvent::class);

        Event::fake();
        $this->deleteJson('/api/v1/entities/1');
        Event::assertDispatched(EntityEvent::class);
    }

    public function test_entity_field_operations_broadcast_events(): void
    {
        $this->getJson('/api/v1/entity-fields');
        Event::assertDispatched(EntityFieldEvent::class);

        Event::fake();
        $this->postJson('/api/v1/entity-fields');
        Event::assertDispatched(EntityFieldEvent::class);

        Event::fake();
        $this->getJson('/api/v1/entity-fields/1');
        Event::assertDispatched(EntityFieldEvent::class);

        Event::fake();
        $this->putJson('/api/v1/entity-fields/1');
        Event::assertDispatched(EntityFieldEvent::class);

        Event::fake();
        $this->deleteJson('/api/v1/entity-fields/1');
        Event::assertDispatched(EntityFieldEvent::class);
    }

    public function test_entity_trigger_operations_broadcast_events(): void
    {
        $this->getJson('/api/v1/entity-triggers');
        Event::assertDispatched(EntityTriggerEvent::class);

        Event::fake();
        $this->postJson('/api/v1/entity-triggers');
        Event::assertDispatched(EntityTriggerEvent::class);

        Event::fake();
        $this->getJson('/api/v1/entity-triggers/1');
        Event::assertDispatched(EntityTriggerEvent::class);

        Event::fake();
        $this->putJson('/api/v1/entity-triggers/1');
        Event::assertDispatched(EntityTriggerEvent::class);

        Event::fake();
        $this->deleteJson('/api/v1/entity-triggers/1');
        Event::assertDispatched(EntityTriggerEvent::class);
    }

    public function test_role_operations_broadcast_events(): void
    {
        $this->getJson('/api/v1/roles');
        Event::assertDispatched(RoleEvent::class);

        Event::fake();
        $this->postJson('/api/v1/roles');
        Event::assertDispatched(RoleEvent::class);

        Event::fake();
        $this->getJson('/api/v1/roles/1');
        Event::assertDispatched(RoleEvent::class);

        Event::fake();
        $this->putJson('/api/v1/roles/1');
        Event::assertDispatched(RoleEvent::class);

        Event::fake();
        $this->deleteJson('/api/v1/roles/1');
        Event::assertDispatched(RoleEvent::class);
    }

    public function test_permission_operations_broadcast_events(): void
    {
        $this->getJson('/api/v1/permissions');
        Event::assertDispatched(PermissionEvent::class);

        Event::fake();
        $this->postJson('/api/v1/permissions');
        Event::assertDispatched(PermissionEvent::class);

        Event::fake();
        $this->getJson('/api/v1/permissions/1');
        Event::assertDispatched(PermissionEvent::class);

        Event::fake();
        $this->putJson('/api/v1/permissions/1');
        Event::assertDispatched(PermissionEvent::class);

        Event::fake();
        $this->deleteJson('/api/v1/permissions/1');
        Event::assertDispatched(PermissionEvent::class);
    }

    public function test_identity_operations_broadcast_events(): void
    {
        $this->getJson('/api/v1/identities');
        Event::assertDispatched(IdentityEvent::class);

        Event::fake();
        $this->postJson('/api/v1/identities');
        Event::assertDispatched(IdentityEvent::class);

        Event::fake();
        $this->getJson('/api/v1/identities/1');
        Event::assertDispatched(IdentityEvent::class);

        Event::fake();
        $this->putJson('/api/v1/identities/1');
        Event::assertDispatched(IdentityEvent::class);

        Event::fake();
        $this->deleteJson('/api/v1/identities/1');
        Event::assertDispatched(IdentityEvent::class);
    }

    public function test_cors_settings_operations_broadcast_events(): void
    {
        $this->getJson('/api/v1/cors-settings');
        Event::assertDispatched(CorsSettingEvent::class);

        Event::fake();
        $this->putJson('/api/v1/cors-settings');
        Event::assertDispatched(CorsSettingEvent::class);
    }
}
