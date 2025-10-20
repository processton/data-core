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
        Schema::create('compliance_features', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Compliance code (e.g., 'GDPR', 'HIPAA')
            $table->string('name'); // Display name
            $table->text('description')->nullable(); // Description of the compliance feature
            $table->boolean('is_enabled')->default(false); // Whether the feature is enabled
            $table->string('module_name')->nullable(); // Module that registered this feature
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_features');
    }
};
