<?php

namespace App\Actions\ComplianceFeature;

use App\Models\ComplianceFeature;

class DisableComplianceFeatureAction
{
    /**
     * Execute the action to disable a compliance feature.
     */
    public function execute(ComplianceFeature $feature): ComplianceFeature
    {
        $feature->update(['is_enabled' => false]);

        return $feature->fresh();
    }
}
