<?php

namespace Tests\Feature;

use App\Http\Controllers\Soap\AccountSoapController;
use App\Http\Controllers\Soap\EntitySoapController;
use App\Models\Account;
use App\Models\Entity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoapApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_wsdl_file_exists(): void
    {
        $wsdlPath = public_path('datacore.wsdl');
        $this->assertFileExists($wsdlPath);

        $content = file_get_contents($wsdlPath);
        $this->assertStringContainsString('<?xml', $content);
        $this->assertStringContainsString('definitions', $content);
        $this->assertStringContainsString('DataCoreService', $content);
    }

    public function test_soap_account_controller_can_get_accounts(): void
    {
        Account::factory()->create(['name' => 'SOAP Test User', 'email' => 'soap@example.com']);

        $controller = new AccountSoapController;
        $result = $controller->getAccounts();

        $this->assertTrue($result['success']);
        $this->assertEquals('Accounts retrieved successfully', $result['message']);
        $this->assertIsArray($result['data']);
        $this->assertCount(1, $result['data']);
    }

    public function test_soap_account_controller_can_create_account(): void
    {
        $controller = new AccountSoapController;
        $result = $controller->createAccount('SOAP User', 'soapuser@example.com', 'user', 'personal');

        $this->assertTrue($result['success']);
        $this->assertEquals('Account created successfully', $result['message']);
        $this->assertDatabaseHas('accounts', [
            'email' => 'soapuser@example.com',
            'name' => 'SOAP User',
        ]);
    }

    public function test_soap_account_controller_can_get_single_account(): void
    {
        $account = Account::factory()->create(['name' => 'SOAP Single User']);

        $controller = new AccountSoapController;
        $result = $controller->getAccount($account->id);

        $this->assertTrue($result['success']);
        $this->assertEquals('Account retrieved successfully', $result['message']);
        $this->assertEquals($account->name, $result['data']['name']);
    }

    public function test_soap_account_controller_can_update_account(): void
    {
        $account = Account::factory()->create(['name' => 'Old Name']);

        $controller = new AccountSoapController;
        $result = $controller->updateAccount($account->id, 'Updated Name');

        $this->assertTrue($result['success']);
        $this->assertEquals('Account updated successfully', $result['message']);
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_soap_account_controller_can_delete_account(): void
    {
        $account = Account::factory()->create();

        $controller = new AccountSoapController;
        $result = $controller->deleteAccount($account->id);

        $this->assertTrue($result['success']);
        $this->assertEquals('Account deleted successfully', $result['message']);
        $this->assertDatabaseMissing('accounts', [
            'id' => $account->id,
        ]);
    }

    public function test_soap_entity_controller_can_get_entities(): void
    {
        Entity::factory()->create(['name' => 'test_entity']);

        $controller = new EntitySoapController;
        $result = $controller->getEntities();

        $this->assertTrue($result['success']);
        $this->assertEquals('Entities retrieved successfully', $result['message']);
        $this->assertIsArray($result['data']);
        $this->assertCount(1, $result['data']);
    }

    public function test_soap_entity_controller_can_create_entity(): void
    {
        $controller = new EntitySoapController;
        $result = $controller->createEntity('test_entity', 'Test Entity', 'test_collection');

        $this->assertTrue($result['success']);
        $this->assertEquals('Entity created successfully', $result['message']);
        $this->assertDatabaseHas('entities', [
            'name' => 'test_entity',
            'display_name' => 'Test Entity',
        ]);
    }

    public function test_api_docs_includes_soap_info(): void
    {
        $response = $this->get('/api/docs');

        $response->assertStatus(200)
            ->assertSee('Data Core API Documentation')
            ->assertSee('Account Management')
            ->assertSee('Entity Management');
    }
}
