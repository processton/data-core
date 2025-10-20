<?php

namespace App\Actions\ComplianceFeature;

use App\Models\ComplianceFeature;

class EnableComplianceFeatureAction
{
    /**
     * Execute the action to enable a compliance feature.
     */
    public function execute(ComplianceFeature $feature): ComplianceFeature
    {
        $feature->update(['is_enabled' => true]);

        return $feature->fresh();
    }
}
