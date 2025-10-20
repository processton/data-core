<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_accounts(): void
    {
        Account::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);

        $response = $this->getJson('/api/v1/accounts');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Accounts retrieved successfully',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'email'],
                ],
            ]);
    }

    public function test_can_create_account(): void
    {
        $accountData = [
            'name' => 'New User',
            'email' => 'new@example.com',
            'role' => 'user',
        ];

        $response = $this->postJson('/api/v1/accounts', $accountData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Account created successfully',
                'data' => [
                    'name' => 'New User',
                    'email' => 'new@example.com',
                ],
            ]);

        $this->assertDatabaseHas('accounts', [
            'email' => 'new@example.com',
        ]);
    }

    public function test_can_get_single_account(): void
    {
        $account = Account::factory()->create(['name' => 'Test User']);

        $response = $this->getJson('/api/v1/accounts/'.$account->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Account retrieved successfully',
                'data' => [
                    'id' => $account->id,
                    'name' => 'Test User',
                ],
            ]);
    }

    public function test_can_update_account(): void
    {
        $account = Account::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson('/api/v1/accounts/'.$account->id, [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Account updated successfully',
                'data' => [
                    'name' => 'Updated Name',
                ],
            ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_account(): void
    {
        $account = Account::factory()->create();

        $response = $this->deleteJson('/api/v1/accounts/'.$account->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Account deleted successfully',
            ]);

        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
        ]);
    }

    public function test_returns_404_for_nonexistent_account(): void
    {
        $response = $this->getJson('/api/v1/accounts/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Account not found',
            ]);
    }

    public function test_validates_account_creation(): void
    {
        $response = $this->postJson('/api/v1/accounts', [
            'name' => '', // Invalid: required
            'email' => 'not-an-email', // Invalid: must be email
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }
}
