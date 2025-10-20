<?php

namespace App\Http\Controllers\Api;

use App\Actions\ComplianceFeature\DisableComplianceFeatureAction;
use App\Actions\ComplianceFeature\EnableComplianceFeatureAction;
use App\Actions\ComplianceFeature\GetComplianceFeatureAction;
use App\Actions\ComplianceFeature\GetComplianceFeaturesAction;
use App\Actions\ComplianceFeature\GetCompliedCodesAction;
use Illuminate\Http\JsonResponse;

/**
 * @group Compliance Feature Management
 *
 * APIs for managing compliance features (Admin only)
 */
class ComplianceFeatureController extends ApiController
{
    /**
     * List compliance features
     *
     * Get a list of all compliance features.
     *
     * @response {
     *  "success": true,
     *  "message": "Compliance features retrieved successfully",
     *  "data": [
     *    {
     *      "id": 1,
     *      "code": "GDPR",
     *      "name": "General Data Protection Regulation",
     *      "description": "EU data protection compliance",
     *      "is_enabled": true,
     *      "module_name": "privacy_module",
     *      "metadata": {},
     *      "created_at": "2025-10-20T12:00:00.000000Z",
     *      "updated_at": "2025-10-20T12:00:00.000000Z"
     *    }
     *  ]
     * }
     */
    public function index(GetComplianceFeaturesAction $action): JsonResponse
    {
        $features = $action->execute();

        return $this->success($features, 'Compliance features retrieved successfully');
    }

    /**
     * Get compliance feature
     *
     * Retrieve a specific compliance feature by its ID.
     *
     * @urlParam id integer required The ID of the compliance feature. Example: 1
     *
     * @response {
     *  "success": true,
     *  "message": "Compliance feature retrieved successfully",
     *  "data": {
     *    "id": 1,
     *    "code": "GDPR",
     *    "name": "General Data Protection Regulation",
     *    "description": "EU data protection compliance",
     *    "is_enabled": true,
     *    "module_name": "privacy_module",
     *    "metadata": {}
     *  }
     * }
     */
    public function show(string $id, GetComplianceFeatureAction $action): JsonResponse
    {
        $feature = $action->execute((int) $id);

        if (! $feature) {
            return $this->error('Compliance feature not found', 404);
        }

        return $this->success($feature, 'Compliance feature retrieved successfully');
    }

    /**
     * Enable compliance feature
     *
     * Enable a specific compliance feature.
     *
     * @urlParam id integer required The ID of the compliance feature. Example: 1
     *
     * @response {
     *  "success": true,
     *  "message": "Compliance feature enabled successfully",
     *  "data": {
     *    "id": 1,
     *    "code": "GDPR",
     *    "name": "General Data Protection Regulation",
     *    "is_enabled": true
     *  }
     * }
     */
    public function enable(string $id, GetComplianceFeatureAction $getAction, EnableComplianceFeatureAction $enableAction): JsonResponse
    {
        $feature = $getAction->execute((int) $id);

        if (! $feature) {
            return $this->error('Compliance feature not found', 404);
        }

        $feature = $enableAction->execute($feature);

        return $this->success($feature, 'Compliance feature enabled successfully');
    }

    /**
     * Disable compliance feature
     *
     * Disable a specific compliance feature.
     *
     * @urlParam id integer required The ID of the compliance feature. Example: 1
     *
     * @response {
     *  "success": true,
     *  "message": "Compliance feature disabled successfully",
     *  "data": {
     *    "id": 1,
     *    "code": "GDPR",
     *    "name": "General Data Protection Regulation",
     *    "is_enabled": false
     *  }
     * }
     */
    public function disable(string $id, GetComplianceFeatureAction $getAction, DisableComplianceFeatureAction $disableAction): JsonResponse
    {
        $feature = $getAction->execute((int) $id);

        if (! $feature) {
            return $this->error('Compliance feature not found', 404);
        }

        $feature = $disableAction->execute($feature);

        return $this->success($feature, 'Compliance feature disabled successfully');
    }

    /**
     * Get complied codes
     *
     * Get only the enabled compliance codes with their descriptions.
     *
     * @response {
     *  "success": true,
     *  "message": "Complied codes retrieved successfully",
     *  "data": [
     *    {
     *      "code": "GDPR",
     *      "description": "EU data protection compliance"
     *    }
     *  ]
     * }
     */
    public function compliedCodes(GetCompliedCodesAction $action): JsonResponse
    {
        $codes = $action->execute();

        return $this->success($codes, 'Complied codes retrieved successfully');
    }
}
