<?php

namespace App\Actions\ComplianceFeature;

use App\Models\ComplianceFeature;
use Illuminate\Database\Eloquent\Collection;

class GetComplianceFeaturesAction
{
    /**
     * Execute the action to retrieve all compliance features.
     */
    public function execute(): Collection
    {
        return ComplianceFeature::all();
    }
}
