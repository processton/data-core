<?php

namespace App\Actions\ComplianceFeature;

use App\Models\ComplianceFeature;
use Illuminate\Support\Collection;

class GetCompliedCodesAction
{
    /**
     * Execute the action to retrieve only enabled compliance codes with their descriptions.
     */
    public function execute(): Collection
    {
        return ComplianceFeature::where('is_enabled', true)
            ->get()
            ->map(function ($feature) {
                return [
                    'code' => $feature->code,
                    'description' => $feature->description,
                ];
            });
    }
}
