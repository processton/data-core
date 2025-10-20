<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cors_settings', function (Blueprint $table) {
            $table->id();
            $table->text('allowed_origins')->nullable();
            $table->text('allowed_methods')->nullable();
            $table->text('allowed_headers')->nullable();
            $table->text('exposed_headers')->nullable();
            $table->integer('max_age')->default(0);
            $table->boolean('supports_credentials')->default(false);
            $table->timestamps();
        });

        // Insert default CORS settings
        DB::table('cors_settings')->insert([
            'allowed_origins' => '*',
            'allowed_methods' => 'GET,POST,PUT,PATCH,DELETE,OPTIONS',
            'allowed_headers' => 'Content-Type,Authorization,X-Requested-With',
            'exposed_headers' => '',
            'max_age' => 0,
            'supports_credentials' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cors_settings');
    }
};
