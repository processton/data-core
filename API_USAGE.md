# API Usage Guide

This guide provides comprehensive examples for using both the REST API and SOAP API in Data Core.

## Table of Contents

- [REST API](#rest-api)
  - [Account Management](#account-management-rest)
  - [Entity Management](#entity-management-rest)
- [SOAP API](#soap-api)
  - [WSDL Access](#wsdl-access)
  - [Account Management](#account-management-soap)
  - [Entity Management](#entity-management-soap)

---

## REST API

The REST API provides JSON-based endpoints for all operations. All REST endpoints are prefixed with `/api/v1/`.

### Base URL

```
http://localhost:8000/api/v1
```

### Account Management (REST)

#### List All Accounts

```bash
curl -X GET http://localhost:8000/api/v1/accounts \
  -H "Content-Type: application/json"
```

**Response:**
```json
{
  "success": true,
  "message": "Accounts retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user",
      "type": "personal",
      "created_at": "2025-10-20T12:00:00.000000Z",
      "updated_at": "2025-10-20T12:00:00.000000Z",
      "usernames": [],
      "identities": []
    }
  ]
}
```

#### Create Account

```bash
curl -X POST http://localhost:8000/api/v1/accounts \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jane Smith",
    "email": "jane@example.com",
    "role": "admin",
    "type": "business"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Account created successfully",
  "data": {
    "id": 2,
    "name": "Jane Smith",
    "email": "jane@example.com",
    "role": "admin",
    "type": "business",
    "created_at": "2025-10-20T12:05:00.000000Z",
    "updated_at": "2025-10-20T12:05:00.000000Z"
  }
}
```

#### Get Single Account

```bash
curl -X GET http://localhost:8000/api/v1/accounts/1 \
  -H "Content-Type: application/json"
```

#### Update Account

```bash
curl -X PUT http://localhost:8000/api/v1/accounts/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Updated"
  }'
```

#### Delete Account

```bash
curl -X DELETE http://localhost:8000/api/v1/accounts/1 \
  -H "Content-Type: application/json"
```

**Response:**
```json
{
  "success": true,
  "message": "Account deleted successfully",
  "data": null
}
```

### Entity Management (REST)

#### List All Entities

```bash
curl -X GET http://localhost:8000/api/v1/entities \
  -H "Content-Type: application/json"
```

#### Create Entity

```bash
curl -X POST http://localhost:8000/api/v1/entities \
  -H "Content-Type: application/json" \
  -d '{
    "name": "products",
    "display_name": "Products",
    "description": "Product catalog entity",
    "collection_name": "products_collection",
    "is_active": true,
    "schema": {
      "type": "object",
      "properties": {
        "title": {"type": "string"},
        "price": {"type": "number"}
      }
    }
  }'
```

#### Get Single Entity

```bash
curl -X GET http://localhost:8000/api/v1/entities/1 \
  -H "Content-Type: application/json"
```

#### Update Entity

```bash
curl -X PUT http://localhost:8000/api/v1/entities/1 \
  -H "Content-Type: application/json" \
  -d '{
    "display_name": "Product Catalog",
    "is_active": true
  }'
```

#### Delete Entity

```bash
curl -X DELETE http://localhost:8000/api/v1/entities/1 \
  -H "Content-Type: application/json"
```

---

## SOAP API

The SOAP API provides WSDL-based endpoints for legacy system integrations. All operations available in REST are also available via SOAP.

### WSDL Access

The WSDL file describes all available SOAP operations and can be accessed at:

```
http://localhost:8000/soap?wsdl
```

### SOAP Endpoint

```
http://localhost:8000/soap
```

### Account Management (SOAP)

#### Get All Accounts

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:getAccounts/>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

**SOAP Request using cURL:**
```bash
curl -X POST http://localhost:8000/soap \
  -H "Content-Type: text/xml; charset=utf-8" \
  -H "SOAPAction: getAccounts" \
  -d '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:getAccounts/>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>'
```

#### Get Single Account

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:getAccount>
            <id>1</id>
        </ns1:getAccount>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

#### Create Account

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:createAccount>
            <name>Jane Smith</name>
            <email>jane@example.com</email>
            <role>admin</role>
            <type>business</type>
        </ns1:createAccount>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

#### Update Account

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:updateAccount>
            <id>1</id>
            <name>John Updated</name>
            <email>john.updated@example.com</email>
            <role>user</role>
            <type>personal</type>
        </ns1:updateAccount>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

#### Delete Account

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:deleteAccount>
            <id>1</id>
        </ns1:deleteAccount>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

### Entity Management (SOAP)

#### Get All Entities

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:getEntities/>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

#### Get Single Entity

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:getEntity>
            <id>1</id>
        </ns1:getEntity>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

#### Create Entity

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:createEntity>
            <name>products</name>
            <display_name>Products</display_name>
            <collection_name>products_collection</collection_name>
            <description>Product catalog entity</description>
            <is_active>true</is_active>
        </ns1:createEntity>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

#### Update Entity

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:updateEntity>
            <id>1</id>
            <name>products</name>
            <display_name>Product Catalog</display_name>
            <collection_name>products_collection</collection_name>
            <description>Updated product catalog</description>
            <is_active>true</is_active>
        </ns1:updateEntity>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

#### Delete Entity

```xml
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope 
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:ns1="http://data-core.processton.com/soap">
    <SOAP-ENV:Body>
        <ns1:deleteEntity>
            <id>1</id>
        </ns1:deleteEntity>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

---

## SOAP Response Format

All SOAP responses follow a consistent structure:

```xml
<SOAP-ENV:Envelope>
    <SOAP-ENV:Body>
        <ns1:getAccountResponse>
            <return>
                <success>true</success>
                <message>Account retrieved successfully</message>
                <data>
                    <!-- Account data here -->
                </data>
            </return>
        </ns1:getAccountResponse>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

---

## Using SOAP with PHP SoapClient

Here's an example of using the SOAP API with PHP's SoapClient:

```php
<?php

// Create SOAP client
$client = new SoapClient('http://localhost:8000/soap?wsdl', [
    'cache_wsdl' => WSDL_CACHE_NONE,
    'trace' => 1,
]);

// Get all accounts
$result = $client->getAccounts();
print_r($result);

// Create account
$result = $client->createAccount(
    'John Doe',           // name
    'john@example.com',   // email
    'user',               // role
    'personal'            // type
);
print_r($result);

// Get single account
$result = $client->getAccount(1);
print_r($result);

// Update account
$result = $client->updateAccount(
    1,                    // id
    'John Updated',       // name
    'john.new@example.com', // email
    'admin',              // role
    'business'            // type
);
print_r($result);

// Delete account
$result = $client->deleteAccount(1);
print_r($result);
```

---

## API Alignment

Both REST and SOAP APIs provide the same functionality:

| Operation | REST Endpoint | SOAP Method |
|-----------|--------------|-------------|
| List accounts | GET /api/v1/accounts | getAccounts() |
| Get account | GET /api/v1/accounts/{id} | getAccount(id) |
| Create account | POST /api/v1/accounts | createAccount(...) |
| Update account | PUT /api/v1/accounts/{id} | updateAccount(id, ...) |
| Delete account | DELETE /api/v1/accounts/{id} | deleteAccount(id) |
| List entities | GET /api/v1/entities | getEntities() |
| Get entity | GET /api/v1/entities/{id} | getEntity(id) |
| Create entity | POST /api/v1/entities | createEntity(...) |
| Update entity | PUT /api/v1/entities/{id} | updateEntity(id, ...) |
| Delete entity | DELETE /api/v1/entities/{id} | deleteEntity(id) |

---

## Error Handling

### REST API Errors

```json
{
  "success": false,
  "message": "Account not found",
  "errors": null
}
```

### SOAP API Errors

```xml
<SOAP-ENV:Envelope>
    <SOAP-ENV:Body>
        <ns1:getAccountResponse>
            <return>
                <success>false</success>
                <message>Account not found</message>
                <data />
            </return>
        </ns1:getAccountResponse>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

---

## Testing the APIs

### Health Check

```bash
curl http://localhost:8000/api/health
```

### API Documentation

```bash
curl http://localhost:8000/api/docs
```

Response includes both REST and SOAP endpoint information:

```json
{
  "message": "API Documentation",
  "version": "1.0.0",
  "rest_endpoints": {
    "/api/v1/accounts": "Account management (REST)",
    "/api/v1/entities": "Entity management (REST)"
  },
  "soap_endpoint": "/soap",
  "wsdl": "/soap?wsdl"
}
```
