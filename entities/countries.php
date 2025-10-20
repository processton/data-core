<?php

/**
 * Countries Entity Configuration
 *
 * This file defines the Countries entity structure, fields, and indexes.
 * Load this entity by running: php artisan entity:load entities/countries.php
 */
return [
    'entities' => [
        [
            'name' => 'countries',
            'display_name' => 'Countries',
            'description' => 'Global countries entity with standardized country information',
            'collection_name' => 'countries_collection',
            'is_active' => true,

            // JSON Schema for validation
            'schema' => [
                'type' => 'object',
                'required' => ['code', 'name'],
                'properties' => [
                    'code' => [
                        'type' => 'string',
                        'description' => 'ISO 3166-1 alpha-2 country code',
                        'pattern' => '^[A-Z]{2}$',
                    ],
                    'name' => [
                        'type' => 'string',
                        'description' => 'Official country name',
                    ],
                    'official_name' => [
                        'type' => 'string',
                        'description' => 'Official full name of the country',
                    ],
                    'alpha3_code' => [
                        'type' => 'string',
                        'description' => 'ISO 3166-1 alpha-3 country code',
                        'pattern' => '^[A-Z]{3}$',
                    ],
                    'numeric_code' => [
                        'type' => 'string',
                        'description' => 'ISO 3166-1 numeric code',
                    ],
                    'capital' => [
                        'type' => 'string',
                        'description' => 'Capital city',
                    ],
                    'region' => [
                        'type' => 'string',
                        'description' => 'Geographic region',
                    ],
                    'subregion' => [
                        'type' => 'string',
                        'description' => 'Geographic subregion',
                    ],
                    'population' => [
                        'type' => 'integer',
                        'description' => 'Total population',
                    ],
                    'area' => [
                        'type' => 'number',
                        'description' => 'Total area in square kilometers',
                    ],
                    'currencies' => [
                        'type' => 'array',
                        'description' => 'List of currencies used',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'code' => ['type' => 'string'],
                                'name' => ['type' => 'string'],
                                'symbol' => ['type' => 'string'],
                            ],
                        ],
                    ],
                    'languages' => [
                        'type' => 'array',
                        'description' => 'Official languages',
                        'items' => ['type' => 'string'],
                    ],
                    'timezones' => [
                        'type' => 'array',
                        'description' => 'Timezones in the country',
                        'items' => ['type' => 'string'],
                    ],
                    'flags' => [
                        'type' => 'object',
                        'description' => 'Flag image URLs',
                        'properties' => [
                            'png' => ['type' => 'string', 'format' => 'uri'],
                            'svg' => ['type' => 'string', 'format' => 'uri'],
                        ],
                    ],
                    'is_active' => [
                        'type' => 'boolean',
                        'description' => 'Whether the country is currently recognized',
                        'default' => true,
                    ],
                ],
            ],

            // Entity fields (for database schema and API)
            'fields' => [
                [
                    'name' => 'code',
                    'display_name' => 'Country Code',
                    'type' => 'string',
                    'description' => 'ISO 3166-1 alpha-2 country code (e.g., US, GB, FR)',
                    'is_required' => true,
                    'is_indexed' => true,
                    'validation_rules' => 'required|string|size:2|regex:/^[A-Z]{2}$/',
                ],
                [
                    'name' => 'name',
                    'display_name' => 'Country Name',
                    'type' => 'string',
                    'description' => 'Common name of the country',
                    'is_required' => true,
                    'is_indexed' => true,
                    'validation_rules' => 'required|string|max:255',
                ],
                [
                    'name' => 'official_name',
                    'display_name' => 'Official Name',
                    'type' => 'string',
                    'description' => 'Official full name',
                    'is_required' => false,
                    'is_indexed' => false,
                    'validation_rules' => 'nullable|string|max:500',
                ],
                [
                    'name' => 'alpha3_code',
                    'display_name' => 'Alpha-3 Code',
                    'type' => 'string',
                    'description' => 'ISO 3166-1 alpha-3 code',
                    'is_required' => false,
                    'is_indexed' => true,
                    'validation_rules' => 'nullable|string|size:3|regex:/^[A-Z]{3}$/',
                ],
                [
                    'name' => 'numeric_code',
                    'display_name' => 'Numeric Code',
                    'type' => 'string',
                    'description' => 'ISO 3166-1 numeric code',
                    'is_required' => false,
                    'is_indexed' => false,
                    'validation_rules' => 'nullable|string|max:3',
                ],
                [
                    'name' => 'capital',
                    'display_name' => 'Capital City',
                    'type' => 'string',
                    'description' => 'Capital city name',
                    'is_required' => false,
                    'is_indexed' => false,
                    'validation_rules' => 'nullable|string|max:255',
                ],
                [
                    'name' => 'region',
                    'display_name' => 'Region',
                    'type' => 'string',
                    'description' => 'Geographic region (e.g., Europe, Asia)',
                    'is_required' => false,
                    'is_indexed' => true,
                    'validation_rules' => 'nullable|string|max:100',
                ],
                [
                    'name' => 'subregion',
                    'display_name' => 'Subregion',
                    'type' => 'string',
                    'description' => 'Geographic subregion',
                    'is_required' => false,
                    'is_indexed' => true,
                    'validation_rules' => 'nullable|string|max:100',
                ],
                [
                    'name' => 'population',
                    'display_name' => 'Population',
                    'type' => 'integer',
                    'description' => 'Total population',
                    'is_required' => false,
                    'is_indexed' => false,
                    'validation_rules' => 'nullable|integer|min:0',
                ],
                [
                    'name' => 'area',
                    'display_name' => 'Area (km²)',
                    'type' => 'decimal',
                    'description' => 'Total area in square kilometers',
                    'is_required' => false,
                    'is_indexed' => false,
                    'validation_rules' => 'nullable|numeric|min:0',
                ],
                [
                    'name' => 'currencies',
                    'display_name' => 'Currencies',
                    'type' => 'json',
                    'description' => 'List of currencies used in the country',
                    'is_required' => false,
                    'is_indexed' => false,
                    'validation_rules' => 'nullable|array',
                ],
                [
                    'name' => 'languages',
                    'display_name' => 'Languages',
                    'type' => 'json',
                    'description' => 'Official languages',
                    'is_required' => false,
                    'is_indexed' => false,
                    'validation_rules' => 'nullable|array',
                ],
                [
                    'name' => 'timezones',
                    'display_name' => 'Timezones',
                    'type' => 'json',
                    'description' => 'Timezones in the country',
                    'is_required' => false,
                    'is_indexed' => false,
                    'validation_rules' => 'nullable|array',
                ],
                [
                    'name' => 'is_active',
                    'display_name' => 'Active',
                    'type' => 'boolean',
                    'description' => 'Whether the country is currently recognized',
                    'is_required' => false,
                    'is_indexed' => true,
                    'validation_rules' => 'nullable|boolean',
                    'default_value' => true,
                ],
            ],

            // MongoDB indexes for optimal query performance
            'indexes' => [
                ['keys' => ['code' => 1], 'options' => ['unique' => true, 'name' => 'code_unique']],
                ['keys' => ['alpha3_code' => 1], 'options' => ['unique' => true, 'sparse' => true, 'name' => 'alpha3_unique']],
                ['keys' => ['name' => 1], 'options' => ['name' => 'name_index']],
                ['keys' => ['region' => 1, 'subregion' => 1], 'options' => ['name' => 'region_index']],
                ['keys' => ['is_active' => 1], 'options' => ['name' => 'active_index']],
            ],
        ],
    ],
];
