<?php

namespace Tests\Feature;

use App\Models\ComplianceFeature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplianceFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_compliance_features(): void
    {
        ComplianceFeature::factory()->create([
            'code' => 'GDPR',
            'name' => 'General Data Protection Regulation',
            'description' => 'EU data protection compliance',
            'is_enabled' => true,
        ]);

        $response = $this->getJson('/api/v1/compliance-features');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Compliance features retrieved successfully',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'code', 'name', 'description', 'is_enabled'],
                ],
            ]);
    }

    public function test_can_get_single_compliance_feature(): void
    {
        $feature = ComplianceFeature::factory()->create([
            'code' => 'HIPAA',
            'name' => 'Health Insurance Portability and Accountability Act',
        ]);

        $response = $this->getJson('/api/v1/compliance-features/'.$feature->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Compliance feature retrieved successfully',
                'data' => [
                    'id' => $feature->id,
                    'code' => 'HIPAA',
                ],
            ]);
    }

    public function test_can_enable_compliance_feature(): void
    {
        $feature = ComplianceFeature::factory()->create([
            'code' => 'GDPR',
            'name' => 'GDPR',
            'is_enabled' => false,
        ]);

        $response = $this->postJson('/api/v1/compliance-features/'.$feature->id.'/enable');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Compliance feature enabled successfully',
                'data' => [
                    'id' => $feature->id,
                    'is_enabled' => true,
                ],
            ]);

        $this->assertDatabaseHas('compliance_features', [
            'id' => $feature->id,
            'is_enabled' => true,
        ]);
    }

    public function test_can_disable_compliance_feature(): void
    {
        $feature = ComplianceFeature::factory()->create([
            'code' => 'GDPR',
            'name' => 'GDPR',
            'is_enabled' => true,
        ]);

        $response = $this->postJson('/api/v1/compliance-features/'.$feature->id.'/disable');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Compliance feature disabled successfully',
                'data' => [
                    'id' => $feature->id,
                    'is_enabled' => false,
                ],
            ]);

        $this->assertDatabaseHas('compliance_features', [
            'id' => $feature->id,
            'is_enabled' => false,
        ]);
    }

    public function test_can_get_complied_codes(): void
    {
        ComplianceFeature::factory()->create([
            'code' => 'GDPR',
            'name' => 'GDPR',
            'description' => 'EU data protection',
            'is_enabled' => true,
        ]);

        ComplianceFeature::factory()->create([
            'code' => 'HIPAA',
            'name' => 'HIPAA',
            'description' => 'Healthcare privacy',
            'is_enabled' => false,
        ]);

        ComplianceFeature::factory()->create([
            'code' => 'SOC2',
            'name' => 'SOC2',
            'description' => 'Security compliance',
            'is_enabled' => true,
        ]);

        $response = $this->getJson('/api/v1/compliance-features/complied-codes');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Complied codes retrieved successfully',
            ])
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'code' => 'GDPR',
                'description' => 'EU data protection',
            ])
            ->assertJsonFragment([
                'code' => 'SOC2',
                'description' => 'Security compliance',
            ])
            ->assertJsonMissing([
                'code' => 'HIPAA',
            ]);
    }

    public function test_returns_404_for_nonexistent_compliance_feature(): void
    {
        $response = $this->getJson('/api/v1/compliance-features/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Compliance feature not found',
            ]);
    }
}
