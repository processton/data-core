# Entity Definitions

This directory contains entity configuration files that define the structure, fields, and behavior of dynamic entities in the Data Core system.

## Overview

Entity configuration files allow you to:
- Define entity schemas with validation rules
- Configure fields with types and constraints
- Set up MongoDB indexes for optimal performance
- Automatically generate REST and SOAP API endpoints

## Loading Entities

To load an entity configuration into the system:

```bash
php artisan entity:load entities/countries.php
```

This command will:
1. Create or update the entity definition in MySQL
2. Sync all entity fields to the database
3. Configure MongoDB indexes (if MongoDB is available)
4. Automatically generate API endpoints

## Entity Configuration Format

Each configuration file must return an array with an `entities` key:

```php
<?php

return [
    'entities' => [
        [
            'name' => 'entity_name',              // Required: Unique entity identifier
            'display_name' => 'Display Name',     // Required: Human-readable name
            'description' => 'Entity description', // Optional: Description
            'collection_name' => 'mongo_collection', // Required: MongoDB collection name
            'is_active' => true,                  // Optional: Default true
            
            // JSON Schema for validation (optional)
            'schema' => [
                'type' => 'object',
                'properties' => [
                    // Define your schema here
                ],
            ],
            
            // Entity fields (for database and API)
            'fields' => [
                [
                    'name' => 'field_name',
                    'display_name' => 'Field Name',
                    'type' => 'string',          // string, integer, boolean, json, etc.
                    'description' => 'Field description',
                    'is_required' => false,
                    'is_indexed' => false,
                    'validation_rules' => 'nullable|string|max:255',
                    'default_value' => null,
                ],
            ],
            
            // MongoDB indexes (optional)
            'indexes' => [
                [
                    'keys' => ['field_name' => 1],
                    'options' => ['unique' => true, 'name' => 'field_unique'],
                ],
            ],
        ],
    ],
];
```

## Available Field Types

- `string`: Text fields
- `integer`: Whole numbers
- `decimal`: Decimal numbers
- `boolean`: True/false values
- `json`: JSON objects or arrays
- `date`: Date values
- `datetime`: Date and time values

## Validation Rules

Use Laravel validation rules in the `validation_rules` field:

- `required`: Field must be present
- `nullable`: Field can be null
- `string`: Must be a string
- `integer`: Must be an integer
- `email`: Must be a valid email
- `max:n`: Maximum length
- `min:n`: Minimum value
- `unique:table,column`: Unique value
- And many more Laravel validation rules

## MongoDB Indexes

Define indexes to optimize query performance:

```php
'indexes' => [
    // Single field index
    ['keys' => ['code' => 1], 'options' => ['unique' => true]],
    
    // Compound index
    ['keys' => ['region' => 1, 'subregion' => 1], 'options' => ['name' => 'region_idx']],
    
    // Sparse index (only indexes documents with the field)
    ['keys' => ['optional_field' => 1], 'options' => ['sparse' => true]],
],
```

Index options:
- `unique`: Enforce unique values
- `sparse`: Only index documents with the field
- `name`: Custom index name
- `background`: Create index in background

## Generated API Endpoints

After loading an entity (e.g., `countries`), the following endpoints are automatically available:

### REST API
- `GET /api/v1/entity/countries` - List all countries
- `GET /api/v1/entity/countries/{id}` - Get single country
- `POST /api/v1/entity/countries` - Create new country
- `PUT /api/v1/entity/countries/{id}` - Update country
- `DELETE /api/v1/entity/countries/{id}` - Delete country

### SOAP API
- `getCountries()` - List all countries
- `getCountry($id)` - Get single country
- `createCountry($data)` - Create new country
- `updateCountry($id, $data)` - Update country
- `deleteCountry($id)` - Delete country

## Example: Countries Entity

See [countries.php](./countries.php) for a complete example of a Countries entity with:
- ISO country codes (alpha-2, alpha-3, numeric)
- Geographic data (region, subregion)
- Population and area
- Currencies and languages
- Timezones
- Flag images
- Comprehensive validation rules
- Optimized MongoDB indexes

Load it with:
```bash
php artisan entity:load entities/countries.php
```

## Creating Custom Entities

1. Create a new PHP file in this directory (e.g., `products.php`)
2. Define your entity structure following the format above
3. Load it with: `php artisan entity:load entities/products.php`
4. Access via the automatically generated API endpoints

## Entity Management Commands

```bash
# Load entity from file
php artisan entity:load entities/countries.php

# Load with verbose output
php artisan entity:load entities/countries.php -v

# Force reload (overwrites existing)
php artisan entity:load entities/countries.php --force
```

## Best Practices

1. **Use descriptive names**: Entity and field names should be clear and self-explanatory
2. **Define validation rules**: Always add appropriate validation rules for data integrity
3. **Index wisely**: Create indexes on frequently queried fields
4. **Document fields**: Add descriptions to help API consumers understand the data
5. **Version your entities**: Keep entity configurations in version control
6. **Test after loading**: Verify entity endpoints work as expected after loading

## Troubleshooting

### Entity not loading
- Check file syntax (must return valid PHP array)
- Ensure all required fields are present
- Check Laravel logs: `storage/logs/laravel.log`

### Validation errors
- Review validation rules in field definitions
- Check that data types match field types
- Ensure required fields have `is_required => true`

### MongoDB indexes not created
- Verify MongoDB connection is configured
- Check MongoDB credentials in `.env`
- Indexes are logged but don't block entity loading

## Additional Resources

- [IMPLEMENTATION_PROTOCOL.md](../IMPLEMENTATION_PROTOCOL.md) - Development guidelines
- [API_USAGE.md](../API_USAGE.md) - API usage examples
- [DEVELOPMENT.md](../DEVELOPMENT.md) - Development setup guide
