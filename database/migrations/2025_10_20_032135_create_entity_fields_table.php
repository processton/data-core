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
        Schema::create('entity_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('entities')->onDelete('cascade');
            $table->string('name');
            $table->string('display_name');
            $table->string('type'); // string, integer, boolean, date, etc.
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_indexed')->default(false);
            $table->json('validation_rules')->nullable();
            $table->json('default_value')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->unique(['entity_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_fields');
    }
};
