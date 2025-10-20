<?php

namespace App\Actions\ComplianceFeature;

use App\Models\ComplianceFeature;

class GetComplianceFeatureAction
{
    /**
     * Execute the action to retrieve a single compliance feature.
     */
    public function execute(int $id): ?ComplianceFeature
    {
        return ComplianceFeature::find($id);
    }
}
