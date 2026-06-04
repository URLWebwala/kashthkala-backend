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
        Schema::create('whatsapp_settings', function (Blueprint $table) {
            $table->id();
            // API Integration
            $table->string('api_endpoint_url')->nullable();
            $table->string('api_access_token')->nullable();
            $table->string('secret_signature')->nullable();
            $table->string('instance_id')->nullable();
            $table->string('webhook_url')->nullable();
            $table->boolean('status')->default(false);

            // Widget Design
            $table->string('whatsapp_number')->nullable();
            $table->string('hover_text')->nullable();
            $table->string('window_header')->nullable();
            $table->string('window_subtitle')->nullable();
            $table->text('welcome_message')->nullable();
            $table->string('button_color')->nullable();
            $table->string('header_color')->nullable();
            $table->string('position')->default('Right');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_settings');
    }
};
