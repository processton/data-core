<?php

namespace Tests\Unit;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthenticationConfigTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function auth_provider_uses_account_model(): void
    {
        $provider = Auth::getProvider();
        $this->assertInstanceOf(\Illuminate\Auth\EloquentUserProvider::class, $provider);
        $this->assertEquals(Account::class, $provider->getModel());
    }

    #[Test]
    public function account_factory_creates_valid_account(): void
    {
        $account = Account::factory()->create();

        $this->assertInstanceOf(Account::class, $account);
        $this->assertNotEmpty($account->name);
        $this->assertNotEmpty($account->email);
        $this->assertEquals('user', $account->role);
        $this->assertEquals('standard', $account->type);
    }

    #[Test]
    public function account_factory_can_create_admin(): void
    {
        $admin = Account::factory()->admin()->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertEquals(config('app.admin_role', 'admin'), $admin->role);
    }

    #[Test]
    public function account_factory_can_create_unverified_account(): void
    {
        $account = Account::factory()->unverified()->create();

        $this->assertNull($account->email_verified_at);
    }

    #[Test]
    public function account_extends_authenticatable(): void
    {
        $account = new Account;

        $this->assertInstanceOf(\Illuminate\Foundation\Auth\User::class, $account);
        $this->assertInstanceOf(\Illuminate\Contracts\Auth\Authenticatable::class, $account);
    }
}
