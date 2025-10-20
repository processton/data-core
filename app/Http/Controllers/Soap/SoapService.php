<?php

namespace App\Http\Controllers\Soap;

class SoapService
{
    protected AccountSoapController $accountController;

    protected EntitySoapController $entityController;

    public function __construct()
    {
        $this->accountController = new AccountSoapController;
        $this->entityController = new EntitySoapController;
    }

    /**
     * Get all accounts.
     */
    public function getAccounts()
    {
        return $this->accountController->getAccounts();
    }

    /**
     * Get a single account.
     */
    public function getAccount($id)
    {
        return $this->accountController->getAccount($id);
    }

    /**
     * Create a new account.
     */
    public function createAccount($name, $email, $role = null, $type = null)
    {
        return $this->accountController->createAccount($name, $email, $role, $type);
    }

    /**
     * Update an existing account.
     */
    public function updateAccount($id, $name = null, $email = null, $role = null, $type = null)
    {
        return $this->accountController->updateAccount($id, $name, $email, $role, $type);
    }

    /**
     * Delete an account.
     */
    public function deleteAccount($id)
    {
        return $this->accountController->deleteAccount($id);
    }

    /**
     * Get all entities.
     */
    public function getEntities()
    {
        return $this->entityController->getEntities();
    }

    /**
     * Get a single entity.
     */
    public function getEntity($id)
    {
        return $this->entityController->getEntity($id);
    }

    /**
     * Create a new entity.
     */
    public function createEntity($name, $display_name, $collection_name, $description = null, $is_active = true)
    {
        return $this->entityController->createEntity($name, $display_name, $collection_name, $description, $is_active);
    }

    /**
     * Update an existing entity.
     */
    public function updateEntity($id, $name = null, $display_name = null, $collection_name = null, $description = null, $is_active = null)
    {
        return $this->entityController->updateEntity($id, $name, $display_name, $collection_name, $description, $is_active);
    }

    /**
     * Delete an entity.
     */
    public function deleteEntity($id)
    {
        return $this->entityController->deleteEntity($id);
    }
}
