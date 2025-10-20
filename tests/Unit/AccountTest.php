<?php

namespace Tests\Unit;

use App\Models\Account;
use Tests\TestCase;

class AccountTest extends TestCase
{
    /**
     * Test that an account can be created.
     */
    public function test_account_can_be_created(): void
    {
        $account = new Account([
            'name' => 'Test Account',
            'email' => 'test@example.com',
            'role' => 'user',
            'type' => 'standard',
        ]);

        $this->assertEquals('Test Account', $account->name);
        $this->assertEquals('test@example.com', $account->email);
        $this->assertEquals('user', $account->role);
        $this->assertEquals('standard', $account->type);
    }

    /**
     * Test that an account can check if it's an admin.
     */
    public function test_account_can_check_if_admin(): void
    {
        $adminAccount = new Account(['role' => 'admin']);
        $userAccount = new Account(['role' => 'user']);

        $this->assertTrue($adminAccount->isAdmin());
        $this->assertFalse($userAccount->isAdmin());
    }
}
