<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entity_identities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('entities')->onDelete('cascade');
            $table->string('identity_key'); // The identity key (e.g., 'uuid', 'code', 'external_id')
            $table->string('identity_value'); // The value of the identity
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();

            // Ensure unique combination of entity_id and identity_key
            $table->unique(['entity_id', 'identity_key', 'identity_value']);
            $table->index(['identity_key', 'identity_value']); // For quick lookups
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_identities');
    }
};
